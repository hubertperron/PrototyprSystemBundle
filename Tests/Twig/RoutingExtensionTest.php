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

    public function testGetRouteMapServiceForEntity()
    {
        $entity = new Page();

        $service = $this->extension->getRouteMapServiceForEntity($entity);

        $this->assertEquals('prototypr_system.route_map.page', $service);
    }

}
