<?php

namespace Prototypr\SystemBundle\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Monolog\Logger;

use Prototypr\SystemBundle\Core\ApplicationKernel;
use Prototypr\SystemBundle\Exception\ApplicationNotBoundException;

/**
 * Main Prototypr kernel
 */
class SystemKernel
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * A collection of available application kernels
     *
     * @var array
     */
    private $applicationKernels;

    /**
     * The currently loaded application kernel
     *
     * @var ApplicationKernel
     */
    private $applicationKernel;

    /**
     * Init
     */
    public function init()
    {
        if (false == $this->applicationKernel instanceof ApplicationKernel) {
            throw new ApplicationNotBoundException();
        }

        $this->applicationKernel->init();

        $this->logger->addInfo('Prototypr initalized using kernel ' . $this->applicationKernel);
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
     * Set the application kernels.
     *
     * @param array $applicationKernels
     */
    public function setApplicationKernels($kernels)
    {
        $this->applicationKernels = $kernels;
    }

    /**
     * Get the application kernels.
     *
     * @return array
     */
    public function getApplicationKernels()
    {
        return $this->applicationKernels;
    }

    /**
     * Add a application kernel.
     *
     * @param ApplicationKernel $kernel
     */
    public function addApplicationKernel($kernel)
    {
        $this->applicationKernels[$kernel->getName()] = $kernel;
    }
}
