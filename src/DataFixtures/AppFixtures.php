<?php

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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use WW\Faker\Provider\Picture;

class AppFixtures extends Fixture
{
    public function __construct(protected UserPasswordEncoderInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        ProviderCollectionHelper::addAllProvidersTo($faker);
        $faker->addProvider(new Picture($faker));

        // User
        $users = [];
        for ($u = 0; $u < 10; $u++) {
            $user = new User();
            $roles = [User::ROLE_USER];
            if ($u === 0) {
                $roles[] = User::ROLE_ADMIN;
            }
            $user
                ->setEmail($u === 0 ? 'admin@symshop.com' : "customer$u@gmail.com")
                ->setPassword($this->passwordEncoder->encodePassword($user, $u === 0 ? 'admin' : 'password'))
                ->setRoles($roles);

            $manager->persist($user);

            // Addresses
            for ($a = 0; $a < random_int(1, 3); $a++) {
                $address = new Address();
                $address
                    ->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setBirthDate($faker->dateTimeThisCentury)
                    ->setStreet($faker->streetAddress)
                    ->setZipCode($faker->postcode)
                    ->setCity($faker->city)
                    ->setCountry($faker->country)
                    ->setAddressName($faker->randomElement(['Maison', 'Travail', 'Parents']))
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
                    ->setVat($faker->randomElement($vatObjects))
                    ->setStock(random_int(1, 20))
                    ->addCategory($category);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
