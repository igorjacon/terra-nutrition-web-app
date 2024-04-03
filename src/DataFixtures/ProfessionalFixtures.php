<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Location;
use App\Entity\Phone;
use App\Entity\Professional;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfessionalFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $professionals = [
            [
                'first_name' => 'Camila',
                'last_name' => 'Ferraz',
                'email' => 'camila.ferraz@terranutri.net',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_NUTRITIONIST]],
                'active' => true,
                'phone' => [
                    'prefix' => '+61',
                    'number' => '80187-909',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'jobTitle' => "Nutritionist",
                'website' => 'https://www.terranutri.net/',
                'tax_number' => '346.005.308-96',
                'locations' => [
                    [
                        'name' => 'Terra Nutrition & Wellness',
                        'phone' => [
                            'prefix' => '+61',
                            'number' => '(04) 80187-909',
                            'flag' => '/build/images/flags/au.svg'
                        ],
                        'address' => [
                            'line_one' => 'Unit 4 - 20 Bay Street - Tweed Heads - 2485',
                            'line_two' => 'Coolangatta',
                            'city' => 'Gold Coast',
                            'zip_code' => '4225',
                            'state' => 'QLD',
                            'country' => 'AU'
                        ]
                    ]
                ]
            ],
            [
                'first_name' => 'Joe',
                'last_name' => 'Doe',
                'password' => 'Password02',
                'email' => 'joe.doe@example.com',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_NUTRITIONIST]],
                'active' => true,
                'phone' => [
                    'prefix' => '+1',
                    'number' => '32334-909',
                    'flag' => '/build/images/flags/us.svg'
                ],
                'jobTitle' => "Nutritionist",
                'website' => 'https://www.example.com/',
                'tax_number' => 'L3433',
                'locations' => [
                    [
                        'name' => 'Health & Fitness',
                        'phone' => [
                            'prefix' => '+1',
                            'number' => '93934 3234',
                            'flag' => '/build/images/flags/us.svg'
                        ],
                        'address' => [
                            'line_one' => '64 Franklin Roosevelt',
                            'line_two' => '',
                            'city' => 'New York City',
                            'zip_code' => '51004',
                            'state' => 'NYC',
                            'country' => 'US'
                        ]
                    ]
                ]
            ]
        ];

        foreach ($professionals as $data) {
            $user = new User();
            $user->setFirstName($data['first_name']);
            $user->setLastName($data['last_name']);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));
            $user->setEmail($data['email']);
            $user->setUsername($data['email']);
            $user->setRoles($data['roles']);
            $user->setEnabled($data['active']);
            $phone = new Phone();
            $phone->setPrefix($data['phone']['prefix']);
            $phone->setNumber($data['phone']['number']);
            $phone->setFlag($data['phone']['flag']);
            $user->addPhone($phone);
            // Professional
            $professional = new Professional();
            $professional->setJobTitle($data['jobTitle']);
            $professional->setWebsite($data['website']);
            $professional->setTaxNumber($data['tax_number']);
            // Location
            foreach ($data['locations'] as $locData) {
                $location = new Location();
                $location->setName($locData['name']);
                $phone = new Phone();
                $phone->setPrefix($locData['phone']['prefix']);
                $phone->setNumber($locData['phone']['number']);
                $phone->setFlag($locData['phone']['flag']);
                $location->setPhone($phone);
                $address = new Address();
                $address->setLineOne($locData['address']['line_one']);
                $address->setLineTwo($locData['address']['line_two']);
                $address->setCity($locData['address']['city']);
                $address->setZipCode($locData['address']['zip_code']);
                $address->setState($locData['address']['state']);
                $address->setCountry($locData['address']['country']);
                $location->setAddress($address);
                $user->setAddress($address);
                $professional->addLocation($location);
            }
            $user->setProfessional($professional);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['professionals'];
    }
}
