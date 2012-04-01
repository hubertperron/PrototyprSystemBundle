<?php

namespace Prototypr\SystemBundle\Tests\Entity;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;
use Prototypr\SystemBundle\Entity\Page;

class PageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Page
     */
    private $page;

    public function setUp()
    {
        $this->page = new Page();
        $this->page->setId(1);
        $this->page->setName('A Product List');
        $this->page->setSlug('product-list');
    }

    public function testGetParents()
    {
        $this->attachSomeParents($this->page);

        $parents = $this->page->getParents();

        $this->assertEquals('level 1', $parents[1]->getName());
        $this->assertEquals('level 2', $parents[2]->getName());
        $this->assertEquals('level 3', $parents[3]->getName());
    }

    public function testGetParentSlugs()
    {
        $this->attachSomeParents($this->page);

        $parents = $this->page->getParentSlugs();

        $this->assertEquals('level-1', $parents[1]);
        $this->assertEquals('level-2', $parents[2]);
        $this->assertEquals('level-3', $parents[3]);
        $this->assertFalse(isset($parents[4]));

        $parents = $this->page->getParentSlugs(true);

        $this->assertEquals('level-1', $parents[1]);
        $this->assertEquals('level-2', $parents[2]);
        $this->assertEquals('level-3', $parents[3]);
        $this->assertEquals('product-list', $parents[4]);
    }

    private function attachSomeParents($page)
    {
        $parentLevel1 = new Page();
        $parentLevel2 = new Page();
        $parentLevel3 = new Page();

        $parentLevel1->setId(2);
        $parentLevel2->setId(3);
        $parentLevel3->setId(4);

        $parentLevel1->setName('level 1');
        $parentLevel2->setName('level 2');
        $parentLevel3->setName('level 3');

        $parentLevel1->setSlug('level-1');
        $parentLevel2->setSlug('level-2');
        $parentLevel3->setSlug('level-3');

        $parentLevel3->setParent($parentLevel2);
        $parentLevel2->setParent($parentLevel1);

        $page->setParent($parentLevel3);
    }

}
