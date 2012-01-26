<?php

namespace Prototypr\SystemBundle\Controller;

/**
 * Prototypr controller Interface
 */
interface ControllerInterface
{
    /**
     * Initialize the controller.
     *
     * @abstract
     */
    function init();

    /**
     * Get the currently loaded application kernel.
     *
     * @abstract
     */
    function getApplicationKernel();
}