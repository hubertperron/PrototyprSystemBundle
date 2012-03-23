<?php

namespace Prototypr\SystemBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Prototypr\SystemBundle\Entity\Application;

class LoadApplicationData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $application = new Application();
        $application->setName('backend');
        $application->setActive(true);

        $manager->persist($application);

        $application = new Application();
        $application->setName('frontend');
        $application->setActive(true);

        $manager->persist($application);

        $manager->flush();
    }
}