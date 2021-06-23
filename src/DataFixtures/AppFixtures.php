<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\PhoneNumber;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $userPasswordHasher = UserPasswordHasherInterface::class;
        $user = new User();
        $user->setFirstName("Admin")
            ->setLastName("User")
            ->setEmail("admin@admin.com")
            ->setRoles(["ROLE_ADMIN"])
            ->setUName(mt_rand(10000000, 99999999))
            ->setDob(DateTime::createFromFormat('Y-m-d', "2021-01-01"));
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, "admin123");
        $user->setPassword($hashedPassword);
        // Set address
        $address = new Address();
        $address->setStreet("345 Backer street");
        $address->setZip(435088);
        $address->setCity("New York City");
        $address->setCountry("US");
        $user->addAddress($address);
        $manager->persist($address);
        // Set Phone number
        $phoneNumber = new PhoneNumber();
        $phoneNumber->setPhoneNo("+1 (165) 848-3343");
        $user->addPhoneNumber($phoneNumber);
        $manager->persist($phoneNumber);
        $manager->persist($user);

        $manager->flush();
    }
}
