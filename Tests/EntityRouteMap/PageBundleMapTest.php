<?php

namespace Prototypr\SystemBundle\Tests\EntityRouteMap;

use Prototypr\SystemBundle\EntityRouteMap\PageBundleMap;
use Prototypr\SystemBundle\Entity\Application;
use Prototypr\SystemBundle\Entity\Bundle;
use Prototypr\SystemBundle\Entity\Page;
use Prototypr\SystemBundle\Entity\PageBundle;

class PageBundleMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PageBundleMap
     */
    protected $pageBundleMap;

    public function setUp()
    {
        $this->pageBundleMap = $this->getMock('Prototypr\SystemBundle\EntityRouteMap\PageBundleMap', array(
            'getRouteCollection',
            'getCurrentApplicationName'
        ));

        $this->pageBundleMap->expects($this->any())
            ->method('getRouteCollection')
            ->will($this->returnValue($this->getRouteCollection()));
    }

    protected function getOptions()
    {
        return array(
            'page' => null,
            'page_application' => null,
            'application' => null,
            'parameters' => null,
            'suffix' => null
        );
    }

    protected function getRouteCollection()
    {
        return array(
            'en__RG__frontend_page_5_news_bundle_frontend' => null,
            'en__RG__frontend_page_5_news_bundle_frontend_detail' => null,
            'en__RG__frontend_page_5_news_bundle_frontend_filter_year' => null,
            'en__RG__frontend_page_5_news_bundle_backend' => null,
            'en__RG__frontend_page_5_news_bundle_backend_edit' => null,

            'en__RG__frontend_page_6_product_bundle_frontend' => null,
            'en__RG__frontend_page_6_product_bundle_frontend_detail' => null,
            'en__RG__frontend_page_6_product_bundle_backend' => null,
            'en__RG__frontend_page_6_product_bundle_backend_edit' => null,

            'en__RG__backend_page_6_user_bundle_backend' => null,
            'en__RG__backend_page_6_user_bundle_backend_edit' => null
        );
    }

    // Context: From anywhere, generate the link to a pageBundle entity.
    // Usage: {{ entity_path(pageBundle) }}
    public function testGetRoute()
    {
        $options = $this->getOptions();

        $frontendApplication = new Application();
        $frontendApplication->setName('frontend');

        $bundle = new Bundle();
        $bundle->setClass('PrototyprProductBundle');

        $page = new Page();
        $page->setId(6);
        $page->setApplication($frontendApplication);

        $pageBundle = new PageBundle();
        $pageBundle->setPage($page);
        $pageBundle->setBundle($bundle);
        $pageBundle->setApplication($frontendApplication);

        $this->pageBundleMap->setEntity($pageBundle);
        $this->assertEquals('en__RG__frontend_page_6_product_bundle_frontend', $this->pageBundleMap->getRoute($options));

        $options['suffix'] = '_list';
        $this->assertEquals('en__RG__frontend_page_6_product_bundle_frontend_list', $this->pageBundleMap->getRoute($options));
        $options['suffix'] = null;

        $pageBundle->setBundleApplication('backend');
        $this->assertEquals('en__RG__frontend_page_6_product_bundle_backend', $this->pageBundleMap->getRoute($options));
    }

}
