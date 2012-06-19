<?php

namespace Prototypr\SystemBundle\Core;

use Symfony\Bundle\DoctrineBundle\Registry;

use Prototypr\SystemBundle\Entity\Page;
use Prototypr\SystemBundle\Entity\Bundle;
use Prototypr\SystemBundle\Entity\PageBundle;
use Prototypr\SystemBundle\Core\SystemKernel;

/**
 * Base implementation of an application kernel
 */
class ApplicationKernel implements ApplicationKernelInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var Bundle
     */
    protected $bundle;

    /**
     * @var PageBundle
     */
    protected $pageBundle;

    /**
     * @var SystemKernel
     */
    protected $systemKernel;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->name) {
            return $this->name;
        }

        return 'UnnamedApplicationKernel';
    }

    /**
     * Init
     */
    public function init()
    {
        // ... add your implementation in the subclasses
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param Bundle $bundle
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * @return Bundle
     */
    public function getBundle()
    {
        if ($this->bundle) {
            return $this->bundle;
        }

        $this->page = $this->doctrine->getEntityManager()->getRepository('PrototyprSystemBundle:Bundle')->find(
            $this->systemKernel->getRequest()->get('_prototypr_bundle_id')
        );

        return $this->bundle;
    }

    /**
     * @param Page $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        if ($this->page) {
            return $this->page;
        }

        $this->page = $this->doctrine->getEntityManager()->getRepository('PrototyprSystemBundle:Page')->find(
            $this->systemKernel->getRequest()->get('_prototypr_page_id')
        );

        return $this->page;
    }

    /**
     * @param PageBundle $pageBundle
     */
    public function setPageBundle($pageBundle)
    {
        $this->pageBundle = $pageBundle;
    }

    /**
     * @return PageBundle
     */
    public function getPageBundle()
    {
        if ($this->pageBundle) {
            return $this->pageBundle;
        }

        $this->pageBundle = $this->doctrine->getEntityManager()->getRepository('PrototyprSystemBundle:PageBundle')->find(
            $this->systemKernel->getRequest()->get('_prototypr_page_bundle_id')
        );

        return $this->pageBundle;
    }

    /**
     * @param SystemKernel $systemKernel
     */
    public function setSystemKernel($systemKernel)
    {
        $this->systemKernel = $systemKernel;
    }

    /**
     * @return SystemKernel
     */
    public function getSystemKernel()
    {
        return $this->systemKernel;
    }

    /**
     * @param Registry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }
}
