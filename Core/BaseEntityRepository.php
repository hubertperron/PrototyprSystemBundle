<?php

namespace Prototypr\SystemBundle\Core;

use Doctrine\ORM\EntityRepository;

use Prototypr\SystemBundle\Core\SystemKernel;

abstract class BaseEntityRepository extends EntityRepository
{
    /**
     * @var SystemKernel
     */
    protected $systemKernel;

    /**
     * @param SystemKernel $systemKernel
     */
    public function setSystemKernel($systemKernel)
    {
        $this->systemKernel = $systemKernel;
    }

    /**
     * @return SystemKernel
     */
    public function getSystemKernel()
    {
        return $this->systemKernel;
    }
}