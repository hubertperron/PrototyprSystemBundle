<?php

namespace Prototypr\SystemBundle\Core;

/**
 * Main Prototypr kernel
 */
class SystemKernel
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $currentApplicationKernel;

    /**
     * Init
     */
    public function init()
    {
        $this->logger->info(get_class($this) . '::INIT');
    }

    /**
     * Set Request
     * Symfony\Component\HttpFoundation
     * @param Symfony\Component\HttpFoundation\Request $request The Request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Set Doctrine
     *
     * @param \Symfony\Bundle\DoctrineBundle\Registry $doctrine Doctrine
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
}
