<?php

// src/DataFixtures/ProductFixtures.php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->paragraph);
            $product->setPrice($faker->randomFloat(2, 10, 100));
            $product->setQuantity($faker->randomNumber(2));
            $product->setImage($faker->imageUrl(200, 200));
            $product->setCreatedAt(new \DateTimeImmutable());
            
            // Set other properties here if needed
            
            $manager->persist($product);
        }

        $manager->flush();
    }
}
