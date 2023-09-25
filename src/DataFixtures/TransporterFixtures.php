<?php
// src/DataFixtures/TransporterFixtures.php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Transporter;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TransporterFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $transporters = [
            ['title' => 'Express Shipping', 'content' => 'Fast delivery', 'price' => 10.00],
            ['title' => 'Standard Shipping', 'content' => 'Regular delivery', 'price' => 5.00],
            ['title' => 'Standard Shipping', 'content' => 'Intern Fret', 'price' => 25.00],
            ['title' => 'Standard Shipping', 'content' => 'DOM delivery', 'price' => 55.00],
            ['title' => 'Standard Shipping', 'content' => 'International delivery', 'price' => 65.00],
            // Add more transporters if needed
        ];

        foreach ($transporters as $transporterData) {
            $transporter = new Transporter();
            $transporter->setTitle($transporterData['title']);
            $transporter->setContent($transporterData['content']);
            $transporter->setPrice($transporterData['price']);

            $manager->persist($transporter);
        }

        $manager->flush();
    }
}
