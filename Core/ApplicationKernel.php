<?php

namespace Prototypr\SystemBundle\Core;

/**
 * Base implementation of an application kernel
 */
abstract class ApplicationKernel implements ApplicationKernelInterface
{

    protected $name;

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
