<?php

namespace Prototypr\SystemBundle\Tests\Core;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;

class SystemKernelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->systemKernel = new SystemKernel();
        $this->applicationKernel = new ApplicationKernel();
        $this->logger = $this->getMock('Symfony\Bridge\Monolog\Logger', array(), array('info'));
    }

    public function testInit()
    {
        $this->logger->expects($this->once())
            ->method('addInfo')
            ->will($this->returnValue(true));

        $this->systemKernel->setLogger($this->logger);
        $this->systemKernel->setApplicationKernel($this->applicationKernel);
        $this->systemKernel->init();

        $this->assertEquals($this->applicationKernel, $this->systemKernel->getApplicationKernel());
    }
}
