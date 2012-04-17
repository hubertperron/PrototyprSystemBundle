<?php

namespace Prototypr\SystemBundle\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Monolog\Logger;

use Prototypr\SystemBundle\Core\ApplicationKernel;
use Prototypr\SystemBundle\Exception\ApplicationNotBoundException;
use Prototypr\SystemBundle\Exception\ApplicationNotFoundException;

/**
 * Main Prototypr kernel
 */
class SystemKernel
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * The currently loaded application kernel
     *
     * @var ApplicationKernel
     */
    protected $applicationKernel;

    /**
     * @var Request
     */
    protected $masterRequest;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->applicationKernels = array();
    }

    /**
     * Init
     *
     * @throws ApplicationNotFoundException
     * @throws ApplicationNotBoundException
     */
    public function init()
    {
        if (false == $this->applicationKernel instanceof ApplicationKernel) {
            throw new ApplicationNotBoundException();
        }

        if (false == in_array($this->applicationKernel->getName(), array_keys($this->applicationKernels))) {
            throw new ApplicationNotFoundException();
        }

        $this->applicationKernel->init();

        $this->logger->addInfo('Prototypr initialized using application kernel ' . $this->applicationKernel);
    }

    /**
     * Set Logger
     *
     * @param Logger $logger Monolog
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ApplicationKernel $kernel
     */
    public function setApplicationKernel($kernel)
    {
        $this->applicationKernel = $kernel;
    }

    /**
     * @return ApplicationKernel
     */
    public function getApplicationKernel()
    {
        return $this->applicationKernel;
    }

    /**
     * @param Request $masterRequest
     */
    public function setMasterRequest($masterRequest)
    {
        $this->masterRequest = $masterRequest;
    }

    /**
     * @return Request
     */
    public function getMasterRequest()
    {
        return $this->masterRequest;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
