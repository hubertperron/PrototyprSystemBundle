<?php

namespace Prototypr\SystemBundle\Core;

/**
 * Default prototypr application kernel implementation
 */
class ApplicationKernel
{
    protected $name;

    public function __toString()
    {
        if ($this->name) {
            return $this->name;
        }

        return 'UnnamedApplicationKernel';
    }

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
