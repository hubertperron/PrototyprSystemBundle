<?php

namespace Prototypr\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prototypr\SystemBundle\Entity\PageTag
 */
class PageTag extends Base
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var Page
     */
    protected $page;


    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set page
     *
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
}