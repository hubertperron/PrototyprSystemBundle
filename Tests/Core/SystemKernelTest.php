<?php

namespace Prototypr\SystemBundle\Tests\Core;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;

class SystemKernelTest extends \PHPUnit_Framework_TestCase
{
    public function testInit()
    {
        $systemKernel = new SystemKernel();
        $systemKernel->init();
    }

    public function testSetCurrentApplicationKernel()
    {
        $applicationKernel = new ApplicationKernel();

        $systemKernel = new SystemKernel();
        $systemKernel->init();
        $systemKernel->setCurrentApplicationKernel($applicationKernel);

        $this->assertEquals($applicationKernel, $systemKernel->getCurrentApplicationKernel());
    }
}
