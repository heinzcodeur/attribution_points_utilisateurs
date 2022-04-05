<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class User extends Fixture
{

    private $encoder;
    private $em;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->encoder = $encoder;
        $this->em =$em;
    }


    public function load(ObjectManager $manager): void
    {


        $faker = Faker\Factory::create('fr_FR');

        for ($n = 1; $n < 36; $n++) {
            $user = new \App\Entity\User();
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, 'azerty'));
            $user->setPrenom($faker->firstName);
            $user->setNom($faker->lastName);
            $user->setCreated(new \DateTime('now'));
            $user->setTotalPoints(rand(100, 500));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
