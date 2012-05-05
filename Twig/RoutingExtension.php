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
            'entity_url' => new \Twig_Function_Method($this, 'getEntityUrl'),
            'entity_path' => new \Twig_Function_Method($this, 'getEntityPath')
        );
    }

    /**
     * @param $entity
     * @param array $parameters
     * @param string $suffix
     * @param string $targetPage
     * @return string
     */
    public function getEntityPath($entity, $parameters = array(), $suffix = null, $targetPage = null)
    {
        $entityRouteMap = $this->container->get($this->getRouteMapService($entity));
        $entityRouteMap->setEntity($entity);

        $name = $entityRouteMap->getFrontendRoute($parameters, $suffix, $targetPage);
        $parameters = $entityRouteMap->getFrontendParameters($parameters, $suffix, $targetPage);

        return $this->generator->generate($name, $parameters, false);
    }

    /**
     * @param $name
     * @param array $parameters
     * @return string
     */
    public function getUrl($name, $parameters = array())
    {
        return $this->generator->generate($name, $parameters, true);
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
