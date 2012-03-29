<?php

namespace Prototypr\SystemBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Prototypr\SystemBundle\DependencyInjection\Compiler\SetRouterPass;
use Prototypr\SystemBundle\DependencyInjection\Compiler\SetEntityManagerPass;

/**
 * Prototypr system bundle
 */
class PrototyprSystemBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SetRouterPass());
        $container->addCompilerPass(new SetEntityManagerPass());
    }
}
