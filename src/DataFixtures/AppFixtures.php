<?php

namespace App\DataFixtures;

use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $test = new Test();
        $test->setTitle('testNam');
        $test->setReleaseYear('2008');
        $test->setDescription("test description");
        $test->setImagePath("imagePath");
        $manager->persist($test);

        $manager->flush();
    }
}
