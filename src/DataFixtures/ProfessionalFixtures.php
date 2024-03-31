<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Location;
use App\Entity\Professional;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfessionalFixtures extends Fixture
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
                'phone' => '(04) 80187-909',
                'jobTitle' => "Nutritionist",
                'website' => 'https://www.terranutri.net/',
                'tax_number' => '346.005.308-96',
                'locations' => [
                    [
                        'name' => 'Terra Nutrition & Wellness',
                        'phone' => '(04) 80187-909',
                        'address' => [
                            'line_one' => 'Unit 4 - 20 Bay Street - Tweed Heads - 2485',
                            'line_two' => 'Coolangatta',
                            'city' => 'Gold Coast',
                            'zip_code' => '4225',
                            'state' => 'QLD',
                            'country' => 'Australia'
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
                'phone' => '+1 32334-909',
                'jobTitle' => "Nutritionist",
                'website' => 'https://www.example.com/',
                'tax_number' => 'L3433',
                'locations' => [
                    [
                        'name' => 'Health & Fitness',
                        'phone' => '+1 93934 3234',
                        'address' => [
                            'line_one' => '64 Franklin Roosevelt',
                            'line_two' => '',
                            'city' => 'New York City',
                            'zip_code' => '51004',
                            'state' => 'NYC',
                            'country' => 'USA'
                        ]
                    ]
                ]
            ]
        ];

        foreach ($professionals as $data) {
            $user = new User();
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $user->setFirstName($data['first_name']);
            $user->setLastName($data['last_name']);
            $user->setPassword($data['password']);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));
            $user->setEmail($data['email']);
            $user->setUsername($data['email']);
            $user->setRoles($data['roles']);
            $user->setEnabled($data['active']);
            $user->setPhoneNumber($data['phone']);
            // Professional
            $professional = new Professional();
            $professional->setUpdatedAt(new \DateTime());
            $professional->setJobTitle($data['jobTitle']);
            $professional->setWebsite($data['website']);
            $professional->setTaxNumber($data['tax_number']);
            // Location
            foreach ($data['locations'] as $locData) {
                $location = new Location();
                $location->setName($locData['name']);
                $location->setPhoneNumber($locData['phone']);
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
}
