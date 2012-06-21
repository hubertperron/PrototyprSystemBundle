<?php

namespace Prototypr\SystemBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

use Symfony\Component\DependencyInjection\Reference;

/**
 * Changes the Router implementation.
 */
class SetRouterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $loaders = array_keys($container->findTaggedServiceIds('prototypr.router_loader'));

        $container->setParameter('jms_i18n_routing.loader.class', $container->getParameter('prototypr_system.router_loader.class'));
        $container->findDefinition('jms_i18n_routing.loader')->addMethodCall('setDoctrine', array($container->findDefinition('doctrine')));
        $container->findDefinition('jms_i18n_routing.loader')->addMethodCall('setContainer', array(new Reference('service_container')));
        $container->findDefinition('jms_i18n_routing.loader')->addMethodCall('setApplicationLoaders', array($loaders));
    }
}