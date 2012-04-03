<?php

namespace Prototypr\SystemBundle\Router;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Bundle\DoctrineBundle\Registry;

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
        $collection = new RouteCollection();

        $application = $this->findApplication();
        $pages = $this->findPages();

        foreach ($pages as $page) {

            $prefix = '';
            if ($application->getRoutingPrefix()) {
                $prefix = $application->getRoutingPrefix() . '/';
            }

            $slugs = implode('/', $page->getParentSlugs(true));

            $defaults = array_merge($this->getRouteDefaults(), array(
                '_prototypr_page_id' => $page->getId(),
                '_prototypr_page_slug' => $page->getSlug(),
                '_prototypr_page_level' => $page->getLevel(),
            ));

            if ($page->hasApplicationBundle($this->applicationName)) {

            }

            $route = new Route(
                $prefix . $slugs,
                $defaults,
                $this->getRouteRequirements(),
                $this->getRouteOptions()
            );

            $collection->add('prototypr_' . $this->applicationName . '_id_' . $page->getId(), $route);
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
        return array();
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
     * @return Application;
     */
    protected function findApplication()
    {
        $em = $this->doctrine->getEntityManager();
        $application = $em->getRepository('PrototyprSystemBundle:Application')->findOneByName($this->applicationName);

        return $application;
    }

}