<?php

namespace Prototypr\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

use Prototypr\SystemBundle\Controller\ControllerInterface;
use Prototypr\SystemBundle\Core\SystemKernel;
use Prototypr\SystemBundle\Core\ApplicationKernel;

class Controller extends BaseController implements ControllerInterface
{
    /**
     * Initialize the controller. This behave in a similar way
     * to the symfony1 preExecute method.
     */
    public function init()
    {
        // ... add your implementation in the subclasses
    }

    /**
     * @return mixed
     */
    public function getApplicationKernel()
    {
        return $this->getSystemKernel()->getCurrentApplicationKernel();
    }


    /**
     * Get the system kernel
     *
     * @return Kernel
     */
    public function getSystemKernel()
    {
        return $this->container->get('prototypr.system.kernel');
    }
}
