<?php

namespace Prototypr\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Prototypr\SystemBundle\Entity\Node;

/**
 * Prototypr\SystemBundle\Entity\Page
 */
class Page extends Node
{
    /**
     * @var Prototypr\SystemBundle\Entity\Page
     */
    protected $children;

    /**
     * @var Prototypr\SystemBundle\Entity\Page
     */
    protected $parent;

    /**
     * @var Prototypr\SystemBundle\Entity\Application
     */
    protected $application;

    /**
     * @var string $name
     */
    protected $name;


    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add children
     *
     * @param Prototypr\SystemBundle\Entity\Page $children
     */
    public function addPage(\Prototypr\SystemBundle\Entity\Page $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param Prototypr\SystemBundle\Entity\Page $parent
     */
    public function setParent(\Prototypr\SystemBundle\Entity\Page $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Prototypr\SystemBundle\Entity\Page 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set application
     *
     * @param Prototypr\SystemBundle\Entity\Application $application
     */
    public function setApplication(\Prototypr\SystemBundle\Entity\Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get application
     *
     * @return Prototypr\SystemBundle\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
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
}