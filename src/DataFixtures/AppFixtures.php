<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Bezhanov\Faker\ProviderCollectionHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use WW\Faker\Provider\Picture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        ProviderCollectionHelper::addAllProvidersTo($faker);
        $faker->addProvider(new Picture($faker));

        // Categories
        for ($c = 0; $c < 5; $c++) {
            $category = new Category();
            $category
                ->setDescription($faker->sentence)
                ->setName($faker->department)
                ->setImageName('placeholder.jpg')
                ->setImageSize(2800);

            $manager->persist($category);

            // Products
            for ($p = 0; $p < random_int(5, 25); $p++) {
                $product = new Product();
                $product
                    ->setName($faker->productName)
                    ->setDescription($faker->sentence)
                    ->setImageName('placeholder.jpg')
                    ->setImageSize(2800)
                    ->setSku($faker->uuid)
                    ->setUnitPrice(random_int(1000, 25000))
                    ->setVat($faker->randomElement([20.0, 10.0, 5.5, 2.1, 0.0]))
                    ->setStock(random_int(1, 20))
                    ->addCategory($category);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
