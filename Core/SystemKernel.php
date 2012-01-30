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
     * Construct
     */
    public function __construct()
    {
        $this->applicationKernels = array();

        // Temporary hardcoded application kernels
        $this->applicationKernels['backend'] = new ApplicationKernel();
        $this->applicationKernels['frontend'] = new ApplicationKernel();
    }

    /**
     * Init
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
