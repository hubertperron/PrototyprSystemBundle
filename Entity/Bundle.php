<?php

namespace Prototypr\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Prototypr\SystemBundle\Entity\Base;

/**
 * Prototypr\SystemBundle\Entity\Bundle
 */
class Bundle extends Base
{

    /**
     * @var string $class
     */
    protected $class;

    /**
     * @var PageBundle
     */
    protected $pageBundles;


    /**
     * Construct
     */
    public function __construct()
    {
        $this->pageBundles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set class
     *
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Add pageBundles
     *
     * @param PageBundle $pageBundles
     */
    public function addPageBundle(PageBundle $pageBundles)
    {
        $this->pageBundles[] = $pageBundles;
    }

    /**
     * Get pageBundles
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPageBundles()
    {
        return $this->pageBundles;
    }
}