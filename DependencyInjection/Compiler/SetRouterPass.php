<?php

namespace Prototypr\SystemBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Changes the Router implementation.
 */
class SetRouterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('jms_i18n_routing.loader.class', $container->getParameter('prototypr.system.router_loader.class'));
        $container->findDefinition('jms_i18n_routing.loader')->addMethodCall('setDoctrine', array($container->findDefinition('doctrine')));
    }
}