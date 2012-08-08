<?php //abc

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

/**
 *
 */
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
                '_prototypr_application_route_prefix' => $application->getRoutingPrefix()
            ));

            $requirements = $this->getRouteRequirements();
            $options = $this->getRouteOptions();

            // Merging the associated bundle entities
            foreach ($page->getPageBundlesForApplication($this->applicationName) as $pageBundle) {

                $defaults['_prototypr_page_bundle_id'] = $pageBundle->getId();
                $defaults['_prototypr_bundle_id'] = $pageBundle->getBundle()->getId();

                $bundle = $pageBundle->getBundle();

                $this->kernel->getBundle($bundle->getClass()); // Validating that the bundle is correctly registered

                // Append each of the bundle routes to the current section route
                foreach ($collection->all() as $name => $route) {

                    if (false == $route->getOption('mergeWithPage')) {
                        continue;
                    }

                    if (false == $this->routeNameCompatibleWithBundleClass($name, $bundle->getClass(), $pageBundle->getBundleApplication())) {
                        continue;
                    }

                    // Bring back the locale to the beginning of the pattern
                    $locales = implode('|', $this->container->getParameter('jms_i18n_routing.locales'));
                    $locale = (preg_match('/(\/' . $locales . ')/', $route->getPattern(), $matches)) ? $matches[1] : '';

                    $route = new Route(
                        str_replace('//', '/', $locale . $pattern . preg_replace('/^' . preg_quote($locale, '/') . '/', '', $route->getPattern())),
                        array_merge($defaults, $route->getDefaults()),
                        array_merge($requirements, $route->getRequirements()),
                        array_merge($options, $route->getOptions())
                    );

                    // Generating a specific route for the page-bundle connection (en__RG__page_id_5_frontend_news_bundle_detail)
                    $collection->add(preg_replace('/(' . I18nLoader::ROUTING_PREFIX . ')/', '$1' . $page->getApplication()->getName() . '_page_' . $page->getId() . '_' , $name), $route);
                }
            }
        }

        return $collection;
    }

    /**
     * routeName: en__RG__news_bundle_frontend
     * bundleClass: PrototyprNewsBundle
     * bundleApplication: NULL
     *
     * @param $routeName
     * @param $bundleClass
     * @param $bundleApplication
     *
     * @return bool
     */
    protected function routeNameCompatibleWithBundleClass($routeName, $bundleClass, $bundleApplication = null)
    {
        $bundleApplication = $bundleApplication ?: $this->applicationName;

        $bundleClass = strtolower(preg_replace('/^Prototypr/', '', $bundleClass));
        $routeName = preg_replace('/(.*)' . preg_quote(I18nLoader::ROUTING_PREFIX) . '/', '', $routeName);
        $routeName = str_replace('_', '', $routeName);

        if (preg_match('/^' . $bundleClass . $bundleApplication . '/', $routeName)) {
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