<?php

namespace Prototypr\SystemBundle\Core;

/**
 * Default prototypr application kernel implementation
 */
class ApplicationKernel
{
    protected $name;

    /**
     * Init
     */
    public function init()
    {
        // ... add your implementation in the subclasses
    }

    public function getName()
    {
        return $this->name;
    }
}
