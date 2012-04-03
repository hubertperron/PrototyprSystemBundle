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
     * @var Prototypr\SystemBundle\Entity\Application
     */
    protected $application;

    /**
     * @var Prototypr\SystemBundle\Entity\PageBundle
     */
    protected $pageBundles;


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
     * Add pageBundles
     *
     * @param Prototypr\SystemBundle\Entity\PageBundle $pageBundles
     */
    public function addPageBundle(\Prototypr\SystemBundle\Entity\PageBundle $pageBundles)
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