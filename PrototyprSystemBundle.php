<?php

namespace Prototypr\SystemBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Prototypr\SystemBundle\DependencyInjection\Compiler\SetRouterPass;

/**
 * Prototypr system bundle
 */
class PrototyprSystemBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SetRouterPass());
    }
}
