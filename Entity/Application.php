<?php

namespace Prototypr\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Prototypr\SystemBundle\Entity\Base;

/**
 * Prototypr\SystemBundle\Entity\Application
 */
class Application extends Base
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var Prototypr\SystemBundle\Entity\Page
     */
    protected $pages;

    /**
     * @var string $routingPrefix
     */
    private $routingPrefix;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Add pages
     *
     * @param Prototypr\SystemBundle\Entity\Page $pages
     */
    public function addPage(\Prototypr\SystemBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
    }

    /**
     * Get pages
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set routingPrefix
     *
     * @param string $routingPrefix
     */
    public function setRoutingPrefix($routingPrefix)
    {
        $this->routingPrefix = $routingPrefix;
    }

    /**
     * Get routingPrefix
     *
     * @return string 
     */
    public function getRoutingPrefix()
    {
        return $this->routingPrefix;
    }
}