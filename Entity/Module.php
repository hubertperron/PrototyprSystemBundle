<?php

namespace Prototypr\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prototypr\SystemBundle\Entity\Module
 */
class Module
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $bundle
     */
    private $bundle;

    /**
     * @var Prototypr\SystemBundle\Entity\Application
     */
    private $application;


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
     * Set bundle
     *
     * @param string $bundle
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * Get bundle
     *
     * @return string 
     */
    public function getBundle()
    {
        return $this->bundle;
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
}