<?php

namespace App\DataFixtures;

use App\Entity\Classes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClassesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        /*for ($i = 1; $i <= 6; $i++){

            $classe = new Classes();

            $classe->setNom($i .'A');

            $manager->persist($classe);
        }

        $manager->flush();*/
    }
}
