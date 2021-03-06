<?php

namespace Prototypr\SystemBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Prototypr\SystemBundle\Entity\Application;

class LoadApplicationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $application = new Application();
        $application->setName('backend');
        $application->setRoutingPrefix('admin');
        $application->setOrdering(1);
        $application->setActive(true);

        $manager->persist($application);

        $application = new Application();
        $application->setName('frontend');
        $application->setRoutingPrefix('');
        $application->setOrdering(2);
        $application->setActive(true);

        $manager->persist($application);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}