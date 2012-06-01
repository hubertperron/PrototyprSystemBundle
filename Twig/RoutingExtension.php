<?php

namespace Prototypr\SystemBundle\Twig;

use Symfony\Component\Routing\Router;
use Symfony\Component\DependencyInjection\Container;

use Prototypr\SystemBundle\Exception\SystemNotInitializedException;
use Prototypr\SystemBundle\Core\SystemKernel;

/**
 * Prototypr routing twig extensions
 */
class RoutingExtension extends \Twig_Extension
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Construct
     *
     * @param $router
     * @param $container
     */
    public function __construct($router, $container)
    {
        $this->generator = $router;
        $this->container = $container;
    }

    /**
     * Get global variables
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'entity_route' => new \Twig_Function_Method($this, 'getEntityRoute'),
            'entity_url' => new \Twig_Function_Method($this, 'getEntityUrl'),
            'entity_path' => new \Twig_Function_Method($this, 'getEntityPath')
        );
    }

    /**
     * @param $entity
     * @param array $options
     * @return array
     */
    public function getEntityRoute($entity, $options = array())
    {
        $entityRouteMap = $this->container->get($this->getRouteMapService($entity));
        $entityRouteMap->setEntity($entity);

        $options = array_merge($this->getDefaultOptions(), $options);

        $name = $entityRouteMap->getRoute($options);
        $parameters = $entityRouteMap->getParameters($options);

        return array(
            'name' => $name,
            'parameters' => $parameters
        );
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function getEntityPath($entity, $options = array())
    {
        $route = $this->getEntityRoute($entity, $options);

        return $this->generator->generate($route['name'], $route['parameters'], false);
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function getUrl($entity, $options = array())
    {
        $route = $this->getEntityRoute($entity, $options);

        return $this->generator->generate($route['name'], $route['parameters'], true);
    }

    /**
     * Get the associated router map service that is associated to an entity.
     *
     * @param $entity
     * @return string
     */
    public function getRouteMapService($entity)
    {
        $entityClass = get_class($entity);

        $matches = array();
        if (preg_match('/.*\\\(.*)Bundle\\\Entity\\\(.*)/', $entityClass, $matches)) {
            return 'prototypr_' . strtolower($matches[1]) . '.route_map.' . strtolower($matches[2]);
        }
    }

    protected function getDefaultOptions()
    {
        return array(
            'page' => null,
            'page_application' => null,
            'application' => null,
            'parameters' => array(),
            'suffix' => null
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'prototypr_routing';
    }

}
