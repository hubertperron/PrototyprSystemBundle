<?php

namespace Prototypr\SystemBundle\Core;

/**
 * Prototypr application kernel Interface
 */
interface ApplicationKernelInterface
{
    /**
     * Initialize the controller.
     *
     * @abstract
     */
    function init();

    function getName();
}