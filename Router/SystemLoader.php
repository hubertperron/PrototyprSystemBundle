<?php

namespace Prototypr\SystemBundle\Router;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Bundle\DoctrineBundle\Registry;

use Prototypr\SystemBundle\Exception\RouterLoaderException;

use JMS\I18nRoutingBundle\Router\I18nLoader as BaseLoader;

class SystemLoader extends BaseLoader
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @param RouteCollection $collection
     * @return RouteCollection
     * @throws RouterLoaderException
     */
    public function load(RouteCollection $collection)
    {
        try {
            $applications = $this->findApplications();

            foreach ($applications as $application) {

                $sectionRepo = $em->getRepository('PrototyprSystemBundle:Page');
                $pages = $sectionRepo->findAll();

                foreach ($pages as $page) {
                    $collection->add('prototypr' . '_' .$application->getName() . '_' . $page->getId(), new Route($page->getTitle()));
                }
            }
        } catch (\Exception $e) {
            throw new RouterLoaderException('[' . get_class($e) . ']' . "\n" . $e->getMessage());
        }

        $collection = parent::load($collection);

        return $collection;
    }

    /**
     * Fetch the application entity.
     *
     * @return array;
     */
    protected function findApplications()
    {
        $em = $this->doctrine->getEntityManager();
        $applications = $em->getRepository('PrototyprSystemBundle:Application')->findByActive(true);

        return $applications;
    }


    /**
     * @param Registry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }
}