<?php

namespace Prototypr\SystemBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;
use Prototypr\SystemBundle\Controller\ControllerInterface;

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
     * @param SystemKernel $systemKernel
     */
    public function __construct($systemKernel)
    {
        $this->systemKernel = $systemKernel;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {

            $controller = $event->getController();
            $controller = $controller[0];

            // The controller must implement the prototypr controller interface
            if (false == $controller instanceof ControllerInterface) {
                return false;
            }

            //! Hardcoded for testing purpose
            $applicationKernel = new ApplicationKernel();
            $applicationKernel->setName('backend');

            // And here we go! (espérons que ça d'jam pas dan'l coude)
            $this->systemKernel->setApplicationKernel($applicationKernel);
            $this->systemKernel->init();

            return true;
        }
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
}