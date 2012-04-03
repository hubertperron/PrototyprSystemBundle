<?php

namespace Prototypr\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Prototypr\SystemBundle\Entity\Base;

/**
 * Prototypr\SystemBundle\Entity\PageBundle
 */
class PageBundle extends Base
{
    /**
     * @var Prototypr\SystemBundle\Entity\Page
     */
    protected $page;

    /**
     * @var Prototypr\SystemBundle\Entity\Bundle
     */
    protected $bundle;


    /**
     * Set page
     *
     * @param Prototypr\SystemBundle\Entity\Page $page
     */
    public function setPage(\Prototypr\SystemBundle\Entity\Page $page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return Prototypr\SystemBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set bundle
     *
     * @param Prototypr\SystemBundle\Entity\Bundle $bundle
     */
    public function setBundle(\Prototypr\SystemBundle\Entity\Bundle $bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * Get bundle
     *
     * @return Prototypr\SystemBundle\Entity\Bundle 
     */
    public function getBundle()
    {
        return $this->bundle;
    }
}