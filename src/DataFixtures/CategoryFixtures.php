<?php
// src/DataFixtures/CategoryFixtures.php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $categories = ['Electronics', 'Clothing', 'Books', 'Home Appliances'];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($category);
        }

        $manager->flush();
    }
}
