<?php

namespace Prototypr\SystemBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Prototypr\SystemBundle\Entity\Application;
use Prototypr\SystemBundle\Entity\Page;

class LoadPageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $doctrine = $this->container->get('doctrine');
        $applications =  $doctrine->getEntityManager()->getRepository('PrototyprSystemBundle:Application')->findAll();

        foreach ($applications as $key => $application) {

            $page = new Page();
            $page->setApplication($application);
            $page->setTitle($application->getName() . ' login');
            $page->setActive(true);
            $page->setOrdering($key + 1);

            $manager->persist($page);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}