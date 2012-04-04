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
     * @return RouteCollection
     * @throws RouterLoaderException
     */
    public function load()
    {
        $parser = new Parser();
        $collection = new RouteCollection();

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
                '_prototypr_page_level' => $page->getLevel(),
            ));

            $requirements = $this->getRouteRequirements();
            $options = $this->getRouteOptions();

            // Merging the associated bundle entities
            foreach ($page->getBundlesForApplication($this->applicationName) as $bundle) {

                $this->kernel->getBundle($bundle->getClass()); // Validating that the bundle is correctly registered

                // TODO: Support multiple file format
                $routes = $parser->parse(file_get_contents($this->kernel->locateResource('@' . $bundle->getName() . '/Resources/config/routing.yml')));

                // Append each of the bundle routes to the current section route
                foreach ($routes as $route) {

                    if (false == $route['options']['mergeWithPage']) {
                        continue;
                    }

                    $pattern = $pattern . '/' . $route['pattern'];
                    $defaults = array_merge($defaults, $route['defaults']);
                    $requirements = array_merge($requirements, $route['requirements']);
                    $options = array_merge($options, $route['options']);

                    $route = new Route(
                        $pattern,
                        $defaults,
                        $requirements,
                        $options
                    );

                    $collection->add('prototypr_' . $this->applicationName . '_id_' . $page->getId(), $route);
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
            'pageDefault' => false
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