<?php

namespace Prototypr\SystemBundle\Tests\EntityRouteMap;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\EntityRouteMap\BundleMap;
use Prototypr\SystemBundle\Entity\Application;
use Prototypr\SystemBundle\Entity\Bundle;
use Prototypr\SystemBundle\Entity\Page;

class BundleMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BundleMap
     */
    protected $bundleMap;

    public function setUp()
    {
        $this->bundleMap = $this->getMock('Prototypr\SystemBundle\EntityRouteMap\BundleMap', array(
            'getRouteCollection',
            'getCurrentApplicationName'
        ));

        $this->bundleMap->expects($this->any())
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

    // Context: From the home page on the frontend, link to the news bundle.
    // Usage: {{ entity_path(newsBundle) }}
    public function testGetRouteFromFrontend()
    {
        $newsBundle = new Bundle();
        $newsBundle->setClass('PrototyprNewsBundle');
        $options = $this->getOptions();

        $this->bundleMap->expects($this->any())
            ->method('getCurrentApplicationName')
            ->will($this->returnValue('frontend'));

        $this->bundleMap->setEntity($newsBundle);
        $this->assertEquals('en__RG__frontend_page_5_news_bundle_frontend', $this->bundleMap->getRoute($options));
    }

    // Context: From the home page on the frontend, link to the news bundle edit page.
    // Usage: {{ entity_path(newsBundle) }}
    public function testGetRouteFromFrontendWithSuffix()
    {
        $newsBundle = new Bundle();
        $newsBundle->setClass('PrototyprNewsBundle');

        $options = $this->getOptions();
        $options['suffix'] = '_detail';

        $this->bundleMap->expects($this->any())
            ->method('getCurrentApplicationName')
            ->will($this->returnValue('frontend'));

        $this->bundleMap->setEntity($newsBundle);
        $this->assertEquals('en__RG__frontend_page_5_news_bundle_frontend_detail', $this->bundleMap->getRoute($options));
    }

    // Context: From the dashboard on the admin panel, link to the user bundle.
    // Usage: {{ entity_path(newsBundle) }}
    public function testGetRouteFromBackend()
    {
        $userBundle = new Bundle();
        $userBundle->setClass('PrototyprUserBundle');
        $options = $this->getOptions();

        $this->bundleMap->expects($this->any())
            ->method('getCurrentApplicationName')
            ->will($this->returnValue('backend'));

        $this->bundleMap->setEntity($userBundle);
        // TODO: supports 'en__RG__backend_page_6_user_bundle' routes (no app-end suffix)
        $this->assertEquals('en__RG__backend_page_6_user_bundle_backend', $this->bundleMap->getRoute($options));
    }

    // Context: From the news admin page, link to the news bundle in the frontend.
    // Usage: {{ entity_path(newsBundle, { 'page_application': 'frontend', 'application': 'frontend' } ) }}
    public function testGetRouteFromBackendToFrontend()
    {
        $newsBundle = new Bundle();
        $newsBundle->setClass('PrototyprNewsBundle');

        $options = $this->getOptions();
        $options['application'] = 'frontend';
        $options['page_application'] = 'frontend';

        $this->bundleMap->expects($this->any())
            ->method('getCurrentApplicationName')
            ->will($this->returnValue('backend'));

        $this->bundleMap->setEntity($newsBundle);
        $this->assertEquals('en__RG__frontend_page_5_news_bundle_frontend', $this->bundleMap->getRoute($options));
    }

    // Context: From the news admin page, link to the product bundle admin.
    // Usage: {{ entity_path(productBundle, { 'page_application': 'frontend' } ) }}
    public function testGetRouteFromAdminFrontendNewsToAdminFrontendProduct()
    {
        $productBundle = new Bundle();
        $productBundle->setClass('PrototyprProductBundle');

        $options = $this->getOptions();
        $options['page_application'] = 'frontend';

        $this->bundleMap->expects($this->any())
            ->method('getCurrentApplicationName')
            ->will($this->returnValue('backend'));

        $this->bundleMap->setEntity($productBundle);
        $this->assertEquals('en__RG__frontend_page_6_product_bundle_backend', $this->bundleMap->getRoute($options));

        // Forcing the application should give the same result
        $options['application'] = 'backend';
        $this->assertEquals('en__RG__frontend_page_6_product_bundle_backend', $this->bundleMap->getRoute($options));
    }

    // Context: From a frontend page, link to the news bundle associated to a specific page.
    // Usage: {{ entity_path(newsBundle, { 'page': page } ) }}
    public function testGetRouteSpecificPage()
    {
        $newsBundle = new Bundle();
        $newsBundle->setClass('PrototyprNewsBundle');

        $application = new Application();
        $application->setName('frontend');

        $page = new Page();
        $page->setId(5);
        $page->setApplication($application);

        $options = $this->getOptions();
        $options['page'] = $page;

        $this->bundleMap->expects($this->any())
            ->method('getCurrentApplicationName')
            ->will($this->returnValue('frontend'));

        $this->bundleMap->setEntity($newsBundle);
        $this->assertEquals('en__RG__frontend_page_5_news_bundle_frontend', $this->bundleMap->getRoute($options));
    }
}
