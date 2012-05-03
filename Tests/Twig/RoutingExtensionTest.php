<?php

namespace Prototypr\SystemBundle\Tests\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Twig\RoutingExtension;
use Prototypr\SystemBundle\Entity\Page;

class RoutingExtensionTest extends WebTestCase
{
    /**
     * @var RoutingExtension
     */
    private $extension;

    public function setUp()
    {
        $this->extension = new RoutingExtension(null, null);
    }

    public function testGetRouteMapService()
    {
        $entity = new Page();

        $service = $this->extension->getRouteMapService($entity);

        $this->assertEquals('prototypr_system.route_map.page', $service);
    }

    public function testGetName()
    {
        $this->assertEquals('prototypr_routing', $this->extension->getName());
    }

    public function testGetFunctions()
    {
        $functions = $this->extension->getFunctions();

        $this->assertArrayHasKey('entity_url', $functions);
        $this->assertArrayHasKey('entity_path', $functions);
    }

}
