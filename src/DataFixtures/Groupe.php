<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class Groupe extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create('fr_FR');

        for($g = 1; $g < 6; $g++){
            $groupe = new \App\Entity\Groupe();
            $groupe->setName($faker->jobTitle);
            $manager->persist($groupe);
        }

        $manager->flush();
    }
}
