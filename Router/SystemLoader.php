<?php

namespace Prototypr\SystemBundle\Router;

use Exception;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\Container;

use Prototypr\SystemBundle\Exception\RouterLoaderException;

use JMS\I18nRoutingBundle\Router\I18nLoader as BaseLoader;

class SystemLoader extends BaseLoader
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var array
     */
    protected $applicationLoaders;

    /**
     * @param RouteCollection $collection
     * @return RouteCollection
     * @throws RouterLoaderException
     */
    public function load(RouteCollection $collection)
    {
        $collection = parent::load($collection);

        try {
            foreach ($this->applicationLoaders as $loader) {
                $this->container->get($loader)->load($collection);
            }
        } catch (Exception $e) {
            throw new RouterLoaderException('[' . get_class($e) . ']' . "\n" . $e->getMessage());
        }

        $collection = $this->cleanCollection($collection);

        return $collection;
    }

    /**
     * Remove the base routes that were used for path merging between the bundles and the pages
     *
     * @param RouteCollection $collection
     * @return RouteCollection
     */
    protected function cleanCollection(RouteCollection $collection)
    {
        foreach ($collection->all() as $name => $route) {
            if (false == $route->getDefault('_prototypr_enabled') && $route->getOption('mergeWithPage')) {
                $collection->remove($name);
            }
        }

        return $collection;
    }

    /**
     * Fetch active application entities from the database.
     *
     * @return array
     */
    protected function findApplications()
    {
        $em = $this->doctrine->getEntityManager();
        $applications = $em->getRepository('PrototyprSystemBundle:Application')->findByActive(true);

        return $applications;
    }

    /**
     * An array of application loaders services
     *
     * @param array $loaders
     */
    public function setApplicationLoaders($loaders)
    {
        $this->applicationLoaders = $loaders;
    }


    /**
     * @param Registry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
}