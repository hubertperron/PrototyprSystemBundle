<?php

namespace Prototypr\SystemBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;
use Prototypr\SystemBundle\Controller\ControllerInterface;
use Prototypr\SystemBundle\Exception\ApplicationNotFoundException;

/**
 * Controller Listener
 */
class ControllerListener
{
    /**
     * @var SystemKernel
     */
    private $systemKernel;

    /**
     * @var array
     */
    private $applicationKernels;

    /**
     * @var ApplicationKernel
     */
    private $currentApplicationKernel;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->applicationKernels = array();
    }

    /**
     * @param FilterControllerEvent $event
     * @throws ApplicationNotFoundException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {

            $controller = $event->getController();
            $controller = $controller[0];

            // The controller must implement the prototypr controller interface
            // to boot the prototypr system
            if (false == $controller instanceof ControllerInterface) {
                return;
            }

            $currentApplicationName = $this->systemKernel->getCurrentApplicationName();

            if (false == in_array($currentApplicationName, array_keys($this->applicationKernels))) {
                throw new ApplicationNotFoundException();
            }

            $currentApplicationKernel = $this->applicationKernels[$currentApplicationName];

            // And here we go! (espérons que ça d'jam pas dan'l coude)
            $this->systemKernel->setCurrentApplicationKernel($currentApplicationKernel);
            $this->systemKernel->init();
            $currentApplicationKernel->init();
            $controller->init();
        }
    }

    /**
     * Add a application kernel
     *
     * @param string $name The application name
     * @param Kernel $kernel The application kernel
     */
    public function addApplicationKernel($name, Kernel $kernel)
    {
        $this->applicationKernels[$name] = $kernel;
    }

    /**
     * Set monolog logger
     *
     * @param Logger $logger The Logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set system kernel
     *
     * @param SystemKernel $kernel The prototypr system kernel
     */
    public function setSystemKernel(SystemKernel $kernel)
    {
        $this->systemKernel = $kernel;
    }

    /**
     * Set the current application kernel
     *
     * @param ApplicationKernel $kernel The prototypr system kernel
     */
    public function setCurrentApplicationKernel(ApplicationKernel $kernel)
    {
        $this->currentApplicationKernel = $kernel;
    }

    /**
     * Get the current application kernel
     *
     * @return ApplicationKernel
     */
    public function getCurrentApplicationKernel()
    {
        return $this->currentApplicationKernel;
    }
}