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
     * @var Page $page
     */
    protected $page;

    /**
     * @var Bundle $bundle
     */
    protected $bundle;

    /**
     * @var string $bundle_application
     */
    protected $bundle_application;

    /**
     * @var boolean $master
     */
    protected $master;

    /**
     * @var Application $application
     */
    protected $application;


    /**
     * Set page
     *
     * @param Page $page
     */
    public function setPage(\Prototypr\SystemBundle\Entity\Page $page)
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

    /**
     * Set bundle
     *
     * @param Bundle $bundle
     */
    public function setBundle(\Prototypr\SystemBundle\Entity\Bundle $bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * Get bundle
     *
     * @return Bundle
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * Set master
     *
     * @param boolean $master
     */
    public function setMaster($master)
    {
        $this->master = $master;
    }

    /**
     * Get master
     *
     * @return boolean 
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * Set application
     *
     * @param Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set bundle_application
     *
     * @param string $bundleApplication
     */
    public function setBundleApplication($bundleApplication)
    {
        $this->bundle_application = $bundleApplication;
    }

    /**
     * Get bundle_application
     *
     * @return string 
     */
    public function getBundleApplication()
    {
        return $this->bundle_application;
    }
}