<?php

namespace Prototypr\SystemBundle\Router;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Yaml\Parser;

use JMS\I18nRoutingBundle\Router\I18nLoader;

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
     * @var Container
     */
    protected $container;

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

                // Append each of the bundle routes to the current section route
                foreach ($collection->all() as $name => $route) {

                    if (false == $this->routeNameMatchBundleClass($name, $bundle->getClass())) {
                        continue;
                    }

                    if (false == $route->getOption('mergeWithPage')) {
                        continue;
                    }

                    // Bring back the locale to the beginning of the pattern
                    $locales = implode('|', $this->container->getParameter('jms_i18n_routing.locales'));
                    if (preg_match('/(\/' . $locales . ')/', $route->getPattern(), $matches)) {
                        $pattern = $matches[1] . $pattern;
                    }

                    $finalPattern = $pattern . $route->getPattern();
                    $finalDefaults = array_merge($defaults, $route->getDefaults());
                    $finalRequirements = array_merge($requirements, $route->getRequirements());
                    $finalOptions = array_merge($options, $route->getRequirements());

                    $route = new Route(
                        $finalPattern,
                        $finalDefaults,
                        $finalRequirements,
                        $finalOptions
                    );

//                    $collection->remove($name);

                    if ($finalOptions['pageDefault']) {
                        $collection->add('prototypr_id_' . $page->getId(), $route);
                    }

                    $collection->add($name, $route);
                }
            }
        }

        return $collection;
    }

    /**
     * routeName: frontend_news_bundle_index
     * bundleName: PrototyprFrontendNewsBundle
     *
     * @param $routeName
     * @param $bundleClass
     * @return bool
     */
    protected function routeNameMatchBundleClass($routeName, $bundleClass)
    {
        $bundleClass = strtolower(preg_replace('/^Prototypr/', '', $bundleClass));
        $routeName = preg_replace('/(.*)' . preg_quote(I18nLoader::ROUTING_PREFIX) . '/', '', $routeName);
        $routeName = str_replace('_', '', $routeName);

        if (preg_match('/^' . $bundleClass . '/', $routeName)) {
            return true;
        }
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

    /**
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

}