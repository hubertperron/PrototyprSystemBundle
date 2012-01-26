<?php

namespace Prototypr\SystemBundle\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Monolog\Logger;

use Prototypr\SystemBundle\Core\ApplicationKernel;

/**
 * Main Prototypr kernel
 */
class SystemKernel
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * The currently loaded application kernel
     *
     * @var ApplicationKernel
     */
    private $currentApplicationKernel;

    /**
     * Init
     */
    public function init()
    {
        // Placeholder for future usefull stuff.
    }

    /**
     * Set Request
     *
     * @param Request $request The Request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Set Doctrine
     *
     * @param Registry $doctrine Doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Set Logger
     *
     * @param \Symfony\Bridge\Monolog\Logger $logger Monolog logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get the current application name
     *
     * @return string|null
     */
    public function getCurrentApplicationName()
    {
        // Match each camel cased token
        $controller = str_replace('\\', '', $this->request->get('_controller'));
        $tokens = preg_split('/(?<=\\w)(?=[A-Z])/', $controller);

        if (isset($tokens[1])) {
            return strtolower($tokens[1]);
        }
    }

    /**
     * @param ApplicationKernel $currentApplicationKernel
     */
    public function setCurrentApplicationKernel($currentApplicationKernel)
    {
        $this->currentApplicationKernel = $currentApplicationKernel;
    }

    /**
     * @return ApplicationKernel
     */
    public function getCurrentApplicationKernel()
    {
        return $this->currentApplicationKernel;
    }
}
