<?php

namespace Prototypr\SystemBundle\Core;

use Closure;
use Exception;

use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityManager as BaseEntityManager;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Prototypr\SystemBundle\Core\BaseEntityRepository;

/**
 * The EntityManager is the central access point to ORM functionality.
 *
 */
class EntityManager extends BaseEntityManager
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Factory method to create EntityManager instances.
     *
     * @param mixed         $conn         An array with the connection parameters or an existing connection instance.
     * @param Configuration $config       The Configuration instance to use.
     * @param EventManager  $eventManager The EventManager instance to use.
     *
     * @return EntityManager The created EntityManager.
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        if (is_array($conn)) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection($conn, $config, ($eventManager ? : new EventManager()));
        } else if ($conn instanceof Connection) {
            if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                throw ORMException::mismatchedEventManager();
            }
        } else {
            throw new \InvalidArgumentException('Invalid argument: ' . $conn);
        }

        // This is where you return an instance of your custom class!
        return new EntityManager($conn, $config, $conn->getEventManager());
    }

    /**
     * Gets the repository and inject the needed dependencies
     *
     * @param string $entityName The name of the entity.
     *
     * @return BaseEntityRepository The repository class.
     */
    public function getRepository($entityName)
    {
        $repository = parent::getRepository($entityName);

        if ($repository instanceof BaseEntityRepository) {
            $repository->setSystemKernel($this->container->get('prototypr.system.kernel'));
        }

        return $repository;
    }

    /**
     * Set container
     *
     * @param ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
}
