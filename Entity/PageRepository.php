<?php

namespace Prototypr\SystemBundle\Entity;

use Prototypr\SystemBundle\Core\BaseEntityRepository;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends BaseEntityRepository
{

    public function findByApplicationNameForNavigation($applicationName)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p', 'a', 'pc', 'pb', 'b')
            ->innerJoin('p.application', 'a')
            ->leftJoin('p.children', 'pc')
            ->leftJoin('p.pageBundles', 'pb')
            ->leftJoin('pb.bundle', 'b')
            ->where('a.name = :name')
            ->setParameter('name', $applicationName);

        return $query->getQuery()->getResult();
    }
}