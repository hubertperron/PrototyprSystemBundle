<?php

namespace Prototypr\SystemBundle\Router;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Bundle\DoctrineBundle\Registry;

use Prototypr\SystemBundle\Exception\RouterLoaderException;
use Prototypr\SystemBundle\Entity\Application;

use JMS\I18nRoutingBundle\Router\I18nLoader as BaseLoader;

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

            $pathSegments = array();

            if ($application->getRoutingPrefix()) {
                $pathSegments[] = $application->getRoutingPrefix();
            }

            $pathSegments = array_merge($pathSegments, $page->getParentSlugs(true));
            $route = new Route(
                implode('/', $pathSegments),
                $this->getRouteDefaults(),
                array(),
                $this->getRouteOptions()
            );

            $collection->add('prototypr_' . $this->applicationName . '_id_' . $page->getId(), $route);
        }

        return $collection;
    }

    protected function getRouteDefaults()
    {
        return array(
            '_prototypr' => true,
            '_application_name' => $this->applicationName
        );
    }

    protected function getRouteOptions()
    {
        return array(
            'i18n' => false
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
     * @return Application;
     */
    protected function findApplication()
    {
        $em = $this->doctrine->getEntityManager();
        $application = $em->getRepository('PrototyprSystemBundle:Application')->findOneByName($this->applicationName);

        return $application;
    }

}