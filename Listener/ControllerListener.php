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
     * @var Logger
     */
    private $logger;

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
            if (false == $controller instanceof ControllerInterface) {
                return;
            }

            //! Hardcoded for testing purpose
            $applicationKernel = new ApplicationKernel();
            $applicationKernel->setName('backend');

            if (false == in_array($applicationKernel->getName(), array_keys($this->systemKernel->getApplicationKernels()))) {
                throw new ApplicationNotFoundException();
            }

            // And here we go! (espérons que ça d'jam pas dan'l coude)
            $this->systemKernel->setCurrentApplicationKernel($applicationKernel);
            $this->systemKernel->init();

            $controller->init();
        }
    }

    /**
     * Add a application kernel
     *
     * @param string $name The application name
     * @param ApplicationKernel $kernel The application kernel
     */
    public function addApplicationKernel($name, $kernel)
    {
        $this->applicationKernels[$name] = $kernel;
    }

    /**
     * Set monolog logger
     *
     * @param Logger $logger The Logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set system kernel
     *
     * @param SystemKernel $kernel The prototypr system kernel
     */
    public function setSystemKernel($kernel)
    {
        $this->systemKernel = $kernel;
    }

    /**
     * Set the current application kernel
     *
     * @param ApplicationKernel $kernel The prototypr system kernel
     */
    public function setCurrentApplicationKernel($kernel)
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