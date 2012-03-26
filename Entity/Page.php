<?php

namespace Prototypr\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prototypr\SystemBundle\Entity\Page
 */
class Page
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var Prototypr\SystemBundle\Entity\Page
     */
    private $parent;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $children;

    /**
     * @var Prototypr\SystemBundle\Entity\Application
     */
    private $application;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var boolean $active
     */
    private $active;

    /**
     * @var integer $ordering
     */
    private $ordering;

    /**
     * @var datetime $createdAt
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     */
    private $updatedAt;

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
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
     * Get the frontend route
     *
     * @param string $suffix
     * @return string
     */
    public function getFrontendRoute($suffix = '')
    {
        $route = 'frontend_' . $this->id;

        if ($suffix) {
            $route .= $suffix;
        }

        return $route;
    }
    /**
     * @var string $slug
     */
    private $slug;


    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
}