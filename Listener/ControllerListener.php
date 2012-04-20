<?php

namespace Prototypr\SystemBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @param Container $container
     */
    public function __construct($systemKernel, $container)
    {
        $this->systemKernel = $systemKernel;
        $this->container = $container;
    }

    /**
     * @param FilterControllerEvent $event
     * @throws ApplicationNotFoundException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $controller = $controller[0];

        if ($this->isPrototyprEnabled($event->getRequestType(), $event->getRequest(), $controller)) {

            if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {

                $applicationName = $event->getRequest()->get('_prototypr_application');

                $applicationKernel = $this->container->get('prototypr.' . $applicationName . '.kernel');
                $applicationKernel->setName($applicationName);

                // And here we go! (espérons que ça d'jam pas dan'l coude)
                $this->systemKernel->setMasterRequest($event->getRequest());
                $this->systemKernel->setApplicationKernel($applicationKernel);
            }

            $this->systemKernel->setRequest($event->getRequest());
            $this->systemKernel->init();

            $controller->init();
        }
    }

    /**
     * Check if the current request is a prototypr compatible one.
     *
     * @param $requestType
     * @param Request $request
     * @param Controller $controller
     *
     * @return bool
     */
    protected function isPrototyprEnabled($requestType, Request $request, $controller)
    {
        // The controller must implement the prototypr controller interface
        if (false == $controller instanceof ControllerInterface) {
            return false;
        }

        if (HttpKernelInterface::MASTER_REQUEST === $requestType) {

            // The current request must have a prototypr enabled parameter
            if (false == $request->get('_prototypr_enabled')) {
                return false;
            }

            // The current request must have a prototypr application parameter defined
            if (false == $applicationName = $request->get('_prototypr_application')) {
                return false;
            }

            // The current application name must have a an associated kernel service
            if (false == $this->container->has('prototypr.' . $applicationName . '.kernel')) {
                return false;
            }
        }

        return true;
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