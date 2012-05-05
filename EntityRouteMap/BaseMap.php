<?php

namespace Prototypr\SystemBundle\EntityRouteMap;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Entity\Page;
use Prototypr\SystemBundle\Entity\Base;

/**
 * Base entity mapping class
 */
abstract class BaseMap
{
    /**
     * @var Base
     */
    protected $entity;

    /**
     * @var SystemKernel
     */
    protected $systemKernel;

    /**
     * @var Router
     */
    protected $router;

    /**
     * Example route name: "en__RG__frontend_page_5_news_bundle_frontend_detail"
     * This method generate the "frontend_page_5" part of the route name.
     *
     * If the target page is null, there is a fallback that try to guess the most appropriate page
     * based on the current request parameters.
     *
     * @param string $route
     * @param Page $targetPage
     *
     * @return string
     */
    public function getNamePrefix($route, Page $targetPage = null)
    {
        // Fallback to the default frontend prefix
        // Default scenario: find the first connected bundle in the current application
        if (null === $targetPage) {

            $application = $this->systemKernel->getApplicationKernel()->getName();
            $collection = $this->router->getRouteCollection();

            foreach ($collection->all() as $name => $_route) {
                $matches = array();
                if (preg_match('/.+(' . $application . '_page_\d+_)' . $route . '.*/', $name, $matches)) {
                    return $matches[1];
                }
            }
        }

        $application = $targetPage->getApplication()->getName();
        $pageId = $targetPage->getId();

        $route = $application . '_page_' . $pageId . '_';

        return $route;
    }

    /**
     * @param Base $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return Base
     */
    public function getEntity()
    {
        return $this->entity;
    }


    /**
     * @param SystemKernel $systemKernel
     */
    public function setSystemKernel($systemKernel)
    {
        $this->systemKernel = $systemKernel;
    }

    /**
     * @return SystemKernel
     */
    public function getSystemKernel()
    {
        return $this->systemKernel;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }
}