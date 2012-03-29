<?php

namespace Prototypr\SystemBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\NoResultException;

use Prototypr\SystemBundle\Entity\Base;

/**
 * Pre Persist Listener
 */
class PrePersistListener
{
    /**
     * Pre Persist
     *
     * @param LifecycleEventArgs $args Arguments
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if (false == $entity instanceof Base) {
            return;
        }

        // Set the value of the ordering field for new entities if an ordering has not been set yet
        if (false == $entity->getId() && false == $entity->getOrdering()) {

            $repo = $entityManager->getRepository(get_class($entity));
            $query = $repo->createQueryBuilder('o')
                ->orderBy('o.ordering', 'DESC')
                ->setMaxResults(1)
                ->getQuery();

            try {
                $maxOrderingEntity = $query->getSingleResult();
                $nextOrdering = $maxOrderingEntity->getOrdering() + 1;
            } catch (NoResultException $e) {
                $nextOrdering = 1;
            }

            $entity->setOrdering($nextOrdering);
        }

    }
}