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
     *
     * @param $applicationName
     * @param $doctrine
     */
    public function __construct($applicationName, $doctrine)
    {
        $this->applicationName = $applicationName;
        $this->doctrine = $doctrine;
    }

    /**
     * @param RouteCollection $collection
     * @return RouteCollection
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
            $pattern = '/' . $pattern;

            $defaults = array_merge($this->getRouteDefaults(), array(
                '_prototypr_page_id' => $page->getId(),
                '_prototypr_page_slug' => $page->getSlug(),
                '_prototypr_page_parent_slugs' => $page->getParentSlugs(),
                '_prototypr_page_parent_slugs_imploded' => implode('/', $page->getParentSlugs()),
                '_prototypr_page_level' => $page->getLevel(),
            ));

            $requirements = $this->getRouteRequirements();
            $options = $this->getRouteOptions();

            // Merging the associated bundle entities
            foreach ($page->getPageBundlesForApplication($this->applicationName) as $pageBundle) {

                $bundle = $pageBundle->getBundle();

                $this->kernel->getBundle($bundle->getClass()); // Validating that the bundle is correctly registered

                // Append each of the bundle routes to the current section route
                foreach ($collection->all() as $name => $route) {

                    if (false == $route->getOption('mergeWithPage')) {
                        continue;
                    }

                    if (false == $this->routeNameMatchBundleClass($name, $bundle->getClass())) {
                        continue;
                    }

                    // Bring back the locale to the beginning of the pattern
                    $locales = implode('|', $this->container->getParameter('jms_i18n_routing.locales'));
                    if (preg_match('/(\/' . $locales . ')/', $route->getPattern(), $matches)) {
                        $locale = $matches[1];
                    } else {
                        $locale = '';
                    }

                    $route = new Route(
                        $locale . $pattern . preg_replace('/^' . preg_quote($locale, '/') . '/', '', $route->getPattern()),
                        array_merge($defaults, $route->getDefaults()),
                        array_merge($requirements, $route->getRequirements()),
                        array_merge($options, $route->getOptions())
                    );

                    /**
                     * A bundle can be connected to one or more pages and
                     * a page can have one or more connected bundles.
                     *
                     * Route generation strategies:
                     *  1. Generating a shortcut route to a bundle using the master page-bundle connection (en__RG__frontend_news_bundle_detail)
                     *  2. Generating a direct route to a page (en__RG__page_id_5 )
                     *  3. Generating a specific route for the page-bundle connection (en__RG__page_id_5_frontend_news_bundle_detail)
                     */

                    if ($pageBundle->getMaster()) {
                        $collection->add($name, $route);
                    } else {
                        $collection->remove($name);
                    }

                    if ($route->getOption('pageDefault') && $pageBundle->getMaster()) {
                        $collection->add($route->getDefault('_locale') . I18nLoader::ROUTING_PREFIX . 'page_id_' . $page->getId(), $route);
                    }

                    $collection->add(preg_replace('/(' . I18nLoader::ROUTING_PREFIX . ')/', '$1page_id_' . $page->getId() . '_' , $name), $route);
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
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

}