<?php

namespace App\DataFixtures;

use App\Entity\Category;
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
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category
                ->setDescription($faker->sentence)
                ->setName($faker->productName)
                ->setImage($faker->pictureUrl(400, 400) . "?random=" . random_int(1, 100000));

            $manager->persist($category);
        }

        $manager->flush();
    }
}
