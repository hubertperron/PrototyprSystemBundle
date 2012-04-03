<?php

namespace Prototypr\SystemBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;

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
    protected $systemKernel;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param SystemKernel $systemKernel
     * @param Request $request
     */
    public function __construct($systemKernel, $container)
    {
        $this->systemKernel = $systemKernel;
        $this->container = $container;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $controller = $controller[0];

        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {

            // The controller must implement the prototypr controller interface
            if (false == $controller instanceof ControllerInterface) {
                return false;
            }

            $applicationName = $event->getRequest()->get('application_name');

            // The current request must have a prototypr application defined
            if (false == $applicationName) {
                return false;
            }

            // The current application name must have a an associated kernel service
            if (false == $this->container->has('prototypr.' . $applicationName . '.kernel')) {
                return false;
            }

            $applicationKernel = $this->container->get('prototypr.' . $applicationName . '.kernel');
            $applicationKernel->setName($applicationName);

            // And here we go! (espérons que ça d'jam pas dan'l coude)
            $this->systemKernel->setApplicationKernel($applicationKernel);
            $this->systemKernel->init();
        }

        // Subrequest
        if ($controller instanceof ControllerInterface) {
            $controller->init();

            return true;
        }

    }

    /**
     * Set monolog logger
     *
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

}