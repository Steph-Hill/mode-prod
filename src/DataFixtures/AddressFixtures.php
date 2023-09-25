<?php
// src/DataFixtures/AddressFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Address;
use App\Entity\Professional;
use Faker\Factory;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Crée des adresses avec des professionnels associés
        for ($i = 0; $i < 20; $i++) {
            $address = new Address();
            $address->setTitle($faker->word);
            $address->setFirstname($faker->firstName);
            $address->setLastname($faker->lastName);
            $address->setCompany($faker->company);
            $address->setAddress($faker->address);
            $address->setPostalcode($faker->postcode);
            $address->setCountry($faker->country);
            $address->setPhone($faker->phoneNumber);
            $address->setCity($faker->city);

            // Associe un professionnel aléatoire à l'adresse
            $professionals = $manager->getRepository(Professional::class)->findAll();
            $address->setProfessional($faker->randomElement($professionals));

            $manager->persist($address);
        }

        $manager->flush();
    }
}
