<?php

namespace Prototypr\SystemBundle\Core;

/**
 * Prototypr application kernel Interface
 */
interface ApplicationKernelInterface
{
    function init();

    function getName();

    function setName($name);
}