<?php

namespace Prototypr\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

use Prototypr\SystemBundle\Controller\ControllerInterface;
use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;

/**
 * Prototypr base controller
 */
class Controller extends BaseController implements ControllerInterface
{
    /**
     * Initialize the controller. This behave in a similar way
     * to the symfony1 preExecute method.
     */
    public function init()
    {
        // ... add your own implementation in the subclass
    }

    /**
     * Get the currently loaded application kernel
     *
     * @return ApplicationKernel
     */
    public function getApplicationKernel()
    {
        return $this->getSystemKernel()->getApplicationKernel();
    }

    /**
     * Get the system kernel
     *
     * @return SystemKernel
     */
    public function getSystemKernel()
    {
        return $this->container->get('prototypr.system.kernel');
    }
}
