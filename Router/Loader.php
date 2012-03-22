<?php

namespace Prototypr\SystemBundle\Router;

use Symfony\Component\Routing\RouteCollection;

use Prototypr\SystemBundle\Exception\RouterLoaderException;
use JMS\I18nRoutingBundle\Router\I18nLoader as BaseLoader;

class Loader extends BaseLoader
{
    private $doctrine;

    /**
     * @param \Symfony\Component\Routing\RouteCollection $collection
     * @return \Symfony\Component\Routing\RouteCollection
     * @throws \Prototypr\SystemBundle\Exception\RouterLoaderException
     */
    public function load(RouteCollection $collection)
    {
        try {
            $em = $this->doctrine->getEntityManager();

            $applications = $em->getRepository('PrototyprSystemBundle:Application')->findByActive(true);

            foreach ($applications as $application) {

                $sectionRepo = $em->getRepository('PrototyprSystemBundle:Page');
                $pages = $sectionRepo->findAll();

                foreach ($pages as $page) {
                    $collection->add($application->getName() . '_' . $page->getId(), new \Symfony\Component\Routing\Route($page->getTitle()));
                }
            }
        } catch (\Exception $e) {
            throw new RouterLoaderException($e->getMessage());
        }

        $collection = parent::load($collection);

        return $collection;
    }

    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }
}