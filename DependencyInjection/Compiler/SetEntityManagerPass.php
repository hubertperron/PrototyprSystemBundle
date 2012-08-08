<?php

namespace Prototypr\SystemBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

use Symfony\Component\DependencyInjection\Reference;

/**
 * Changes the Router implementation.
 */
class SetEntityManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('doctrine.orm.entity_manager.class', 'Prototypr\SystemBundle\Core\EntityManager');
//        $container->findDefinition('doctrine.orm.entity_manager')->addMethodCall('setContainer', array(new Reference('service_container')));
    }
}