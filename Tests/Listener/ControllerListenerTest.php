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
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request', array(), array(), '', true, false);
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\Kernel', array(), array('dev', true));
        $this->controller = $this->getMock('Prototypr\SystemBundle\Controller\Controller');

        $this->systemKernel = $this->getMock('Prototypr\SystemBundle\Core\SystemKernel');
        $this->applicationKernel = new ApplicationKernel();

        $this->controllerListener = new ControllerListener($this->systemKernel, $this->container);
    }

    public function testInit()
    {
        // Testing with a master request that should launch prototypr
        $filterControllerEvent = new FilterControllerEvent(
            $this->kernel,
            array($this->controller, 'init'),
            $this->request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->request->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('_prototypr_enabled'))
            ->will($this->returnValue(true));

        $this->request->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('_prototypr_application'))
            ->will($this->returnValue('extranet'));

        $this->request->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo('_prototypr_application'))
            ->will($this->returnValue('extranet'));

        $this->container->expects($this->once())
            ->method('has')
            ->with($this->equalTo('prototypr.extranet.kernel'))
            ->will($this->returnValue(true));

        $this->container->expects($this->any())
            ->method('get')
            ->with($this->equalTo('prototypr.extranet.kernel'))
            ->will($this->returnValue($this->applicationKernel));

        $this->controllerListener->onKernelController($filterControllerEvent);
        $this->assertEquals('extranet', $this->applicationKernel->getName());

        // Testing with a subrequest that should do nothing
        $filterControllerEvent = new FilterControllerEvent(
            $this->kernel,
            array($this->controller, 'init'),
            $this->request,
            HttpKernelInterface::SUB_REQUEST
        );

        $this->assertNull($this->controllerListener->onKernelController($filterControllerEvent));
    }
}
