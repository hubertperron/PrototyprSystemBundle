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
     * @var String
     */
    protected $applicationName;

    /**
     * @param RouteCollection $collection
     * @return RouteCollection
     * @throws RouterLoaderException
     */
    public function load(RouteCollection $collection)
    {
        $pages = $this->findPages();

        foreach ($pages as $page) {
            $collection->add('prototypr' . '_' .$application->getName() . '_' . $page->getId(), new Route($page->getTitle()));
        }

        return $collection;
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
        $pages =    $em->getRepository('PrototyprSystemBundle:Page')->findByApplication($this->findApplication()->getId());

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

    /**
     * @param Registry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param string $applicationName
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = strtolower($applicationName);
    }
}