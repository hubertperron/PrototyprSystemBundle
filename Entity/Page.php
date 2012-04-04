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
     * @var Prototypr\SystemBundle\Entity\PageBundle
     */
    protected $pageBundles;


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
     * Get parents
     *
     * @return array
     */
    public function getParents()
    {
        $level = 1;

        $parentsDesc = array();
        $parentsAsc = array();
        $parent = $this->getParent();

        while ($parent && $parent->getId()) {
            $parentsDesc[] = $parent;
            $parent = $parent->getParent();
        }

        foreach (array_reverse($parentsDesc) as $parent) {
            $parentsAsc[$level++] = $parent;
        }

        return $parentsAsc;
    }

    /**
     * Get an array of each slug in the parent hierarchy indexed by lvel number
     *
     * @param bool $includeCurrent
     * @return array
     */
    public function getParentSlugs($includeCurrent = false)
    {
        $level = 0;
        $parents = $this->getParents();
        $slugs = array();

        foreach ($parents as $level => $parent) {
            $slugs[$level] = $parent->getSlug();
        }

        if ($includeCurrent) {
            $slugs[++$level] = $this->slug;
        }

        return $slugs;
    }

    /**
     * Get the tree level number
     *
     * @return int
     */
    public function getLevel()
    {
        return count($this->getParents()) + 1;
    }

    /**
     * Get every bundle entities connected to this page for a given application
     *
     * @param $applicationName
     * @return array
     */
    public function getBundlesForApplication($applicationName)
    {
        $bundles = array();

        foreach ($this->pageBundles as $pageBundle) {
            if ($pageBundle->getBundle()->getApplication()->getName() == $applicationName) {
                $bundles[] = $pageBundle->getBundle();
            }
        }

        return $bundles;
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