<?php

namespace Prototypr\SystemBundle\Router;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Yaml\Parser;

use Prototypr\SystemBundle\Exception\RouterLoaderException;
use Prototypr\SystemBundle\Entity\Application;

class ApplicationLoader
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $applicationName;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * Construct
     */
    public function __construct($applicationName, $doctrine)
    {
        $this->applicationName = $applicationName;
        $this->doctrine = $doctrine;
    }

    /**
     * @param RouteCollection
     * @return RouteCollection
     * @throws RouterLoaderException
     */
    public function load(RouteCollection $collection)
    {
        $parser = new Parser();

        $application = $this->findApplication();
        $pages = $this->findPages();

        foreach ($pages as $page) {

            $pattern = implode('/', $page->getParentSlugs(true));

            if ($application->getRoutingPrefix()) {
                $pattern = $application->getRoutingPrefix() . '/' . $pattern;
            }

            $defaults = array_merge($this->getRouteDefaults(), array(
                '_prototypr_page_id' => $page->getId(),
                '_prototypr_page_slug' => $page->getSlug(),
                '_prototypr_page_parent_slugs' => implode('/', $page->getParentSlugs()),
                '_prototypr_page_level' => $page->getLevel(),
            ));

            $requirements = $this->getRouteRequirements();
            $options = $this->getRouteOptions();

            // Merging the associated bundle entities
            foreach ($page->getBundlesForApplication($this->applicationName) as $bundle) {

                $this->kernel->getBundle($bundle->getClass()); // Validating that the bundle is correctly registered

                // TODO: Support multiple file format
                $routes = $parser->parse(file_get_contents($this->kernel->locateResource('@' . $bundle->getClass() . '/Resources/config/routing.yml')));

                // Append each of the bundle routes to the current section route
                foreach ($routes as $name => $route) {

                    $mergedPattern = $pattern . $route['pattern'];
                    $mergedDefaults = array_merge($defaults, $route['defaults']);
                    $mergedRequirements = array_merge($requirements, isset($route['requirements']) ? $route['requirements'] : array());
                    $mergedOptions = array_merge($options, isset($route['options']) ? $route['options'] : array());

                    if (false == $mergedOptions['mergeWithPage']) {
                        continue;
                    }

                    $route = new Route(
                        $mergedPattern,
                        $mergedDefaults,
                        $mergedRequirements,
                        $mergedOptions
                    );

                    echo $name;
                    $collection->remove($name);

                    if ($mergedOptions['pageDefault']) {
                        $collection->add('prototypr_' . $this->applicationName . '_id_' . $page->getId(), $route);
                    }

                    $collection->add('prototypr_' . $this->applicationName . '_' . $name, $route);
                }
            }
        }

        return $collection;
    }

    /**
     * @return array
     */
    protected function getRouteDefaults()
    {
        return array(
            '_prototypr_enabled' => true,
            '_prototypr_application' => $this->applicationName
        );
    }

    /**
     * @return array
     */
    protected function getRouteRequirements()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function getRouteOptions()
    {
        return array(
            'mergeWithPage' => false,
            'pageDefault' => false,
//            'i18n' => false // Disable JMSI18nRouting route handling, this loader (will) handle I18n on his own.
        );
    }

    /**
     * Fetch the associated page entities of an application.
     *
     * @param integer $applicationId
     * @return array
     */
    protected function findPages()
    {
        $em = $this->doctrine->getEntityManager();
        $pages = $em->getRepository('PrototyprSystemBundle:Page')->findByApplication($this->findApplication()->getId());

        return $pages;
    }

    /**
     * Fetch the application entity.
     *
     * @return Application
     */
    protected function findApplication()
    {
        $em = $this->doctrine->getEntityManager();
        $application = $em->getRepository('PrototyprSystemBundle:Application')->findOneByName($this->applicationName);

        return $application;
    }

    /**
     * @param Kernel $kernel
     */
    public function setKernel($kernel)
    {
        $this->kernel = $kernel;
    }

}