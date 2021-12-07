<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Vat;
use Bezhanov\Faker\ProviderCollectionHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        ProviderCollectionHelper::addAllProvidersTo($faker);

        for ($u = 0; $u < 10; $u++) {
            $user = new User();
            $roles = [User::ROLE_USER];
            if ($u === 0) {
                $roles[] = User::ROLE_ADMIN;
            }
            $user
                ->setLastName($u === 0 ? 'Admin' : $faker->lastName)
                ->setFirstName($u === 0 ? 'Admin' : $faker->firstName)
                ->setEmail($u === 0 ? 'admin@symshop.com' : "customer$u@gmail.com")
                ->setPassword($this->passwordHasher->hashPassword($user, $u === 0 ? 'admin' : 'password'))
                ->setRoles($roles);

            $manager->persist($user);

            // Addresses
            for ($a = 0; $a < $faker->numberBetween(1, 3); $a++) {
                $address = new Address();
                $address
                    ->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setBirthDate($faker->dateTimeThisCentury)
                    ->setStreet($faker->streetAddress)
                    ->setZipCode($faker->postcode)
                    ->setCity($faker->city)
                    ->setCountry($faker->country)
                    ->setAddressName($faker->randomElement(['Maison', 'Travail', 'Autre']))
                    ->setInvoiceDefault($a === 0)
                    ->setDeliveryDefault($a === 0)
                    ->setUser($user);
                $user->addAddress($address);
                $manager->persist($address);
            }
        }

        // Vat
        $vats = [20.0, 10.0, 5.5, 2.1, 0.0];
        $vatObjects = [];
        foreach ($vats as $val) {
            $vat = new Vat();
            $vat->setLabel($val . ' %')
                ->setValue($val);
            $manager->persist($vat);
            $vatObjects[] = $vat;
        }

        // Categories
        for ($c = 0; $c < 5; $c++) {
            $category = new Category();
            $category
                ->setDescription($faker->sentence)
                ->setName($faker->department);

            $manager->persist($category);

            // Products
            for ($p = 0; $p < $faker->numberBetween(1, 3); $p++) {
                $product = new Product();
                $product
                    ->setName($faker->productName)
                    ->setDescription($faker->sentence)
                    ->setImageName('placeholder.jpg')
                    ->setImageSize(2800)
                    ->setSku($faker->uuid)
                    ->setUnitPrice($faker->numberBetween(1000, 50000))
                    ->setVat($faker->randomElement($vatObjects))
                    ->setStock($faker->numberBetween(0, 50))
                    ->setCategory($category)
                    ->setDiscountPercentage($faker->randomElement([0, 15, 25, 30, 50]));

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
