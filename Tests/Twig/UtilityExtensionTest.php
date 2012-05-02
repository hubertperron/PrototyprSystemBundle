<?php

namespace Prototypr\SystemBundle\Tests\Twig;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Twig\UtilityExtension;

class UtilityExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SystemKernel
     */
    private $systemKernel;

    /**
     * @var Extension
     */
    private $extension;

    public function setUp()
    {
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request', array(), array(), '', true, false);
        $this->systemKernel = $this->getMock('Prototypr\SystemBundle\Core\SystemKernel');

        $this->systemKernel->expects($this->any())
            ->method('getMasterRequest')
            ->will($this->returnValue($this->request));

        $this->extension = new UtilityExtension($this->systemKernel);
    }

    public function testGetActionName()
    {
        $this->request->expects($this->once())
        ->method('get')
        ->with($this->equalTo('_controller'))
        ->will($this->returnValue('Prototypr\NewsBundle\Controller\Frontend\NewsController::indexAction'));

        $this->assertEquals('index', $this->extension->getActionName());
    }

    public function testGetApplicationName()
    {
        $this->request->expects($this->once())
            ->method('get')
            ->with($this->equalTo('_prototypr_application'))
            ->will($this->returnValue('extranet'));

        $this->assertEquals('extranet', $this->extension->getApplicationName());
    }

    public function testGetBundleName()
    {
        $this->request->expects($this->once())
            ->method('get')
            ->with($this->equalTo('_controller'))
            ->will($this->returnValue('Prototypr\NewsBundle\Controller\Frontend\NewsController::indexAction'));

        $this->assertEquals('news', $this->extension->getBundleName());
    }

    public function testGetControllerName()
    {
        $this->request->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('_controller'))
            ->will($this->returnValue('Prototypr\NewsBundle\Controller\Frontend\NewsController::indexAction'));

        $this->assertEquals('frontend_news', $this->extension->getControllerName());

        $this->request->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('_controller'))
            ->will($this->returnValue('Prototypr\NewsBundle\Controller\NewsController::indexAction'));

        $this->assertEquals('news', $this->extension->getControllerName());

        $this->request->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('_controller'))
            ->will($this->returnValue('Prototypr\NewsBundle\Controller\SeriousNewsController::indexAction'));

        $this->assertEquals('seriousnews', $this->extension->getControllerName());
    }

    public function testGetName()
    {
        $this->assertEquals('prototypr_system', $this->extension->getName());
    }

    public function testGetGlobals()
    {
        $globals = $this->extension->getGlobals();

        $this->assertArrayHasKey('action_name', $globals);
        $this->assertArrayHasKey('application_name', $globals);
        $this->assertArrayHasKey('controller_name', $globals);
        $this->assertArrayHasKey('bundle_name', $globals);
    }
}
