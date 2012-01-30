<?php

namespace Prototypr\SystemBundle\Tests\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Prototypr\SystemBundle\Controller\ControllerInterface;
use Prototypr\SystemBundle\Listener\ControllerListener;
use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;


class ControllerListenerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->controllerListener = new ControllerListener($this->getMock('Prototypr\SystemBundle\Core\SystemKernel'));
        $this->logger = $this->getMock('Symfony\Bridge\Monolog\Logger', array(), array('info'));
    }

    public function testInit()
    {
        $filterControllerEvent = new FilterControllerEvent(
            $this->getMock('Symfony\Component\HttpKernel\Kernel', array(), array('dev', true)),
            array($this->getMock('Prototypr\SystemBundle\Controller\Controller'), 'init'),
            $this->getMock('Symfony\Component\HttpFoundation\Request'),
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->assertEquals(true, $this->controllerListener->onKernelController($filterControllerEvent));
    }
}
