<?php

namespace Prototypr\SystemBundle\Router;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Finder\Finder;

class Cache
{
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @param Kernel $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Invalidate the routing cache
     */
    public function invalidate()
    {
        $finder = new Finder();
        $cacheDir = $this->kernel->getCacheDir();

        foreach ($finder->files()->name('/(.*)Url(Matcher|Generator)(.*)/')->in($cacheDir) as $file) {
            unlink($file);
        }
    }
}