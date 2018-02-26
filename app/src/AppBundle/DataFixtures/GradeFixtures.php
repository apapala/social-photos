<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Grade;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GradeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 11; $i++) {
            $grade = new Grade();
            $grade->setGrade($i);
            $manager->persist($grade);
        }

        $manager->flush();
    }
}