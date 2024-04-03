<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $customers = [
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '412 345 678',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '123 Smith Street',
                    'line_two' => '',
                    'city' => 'Sydney',
                    'zip_code' => '2000',
                    'state' => 'NSW',
                    'country' => 'AU'
                ],
                'age' => 30,
                'height' => '165 cm',
                'weight' => '60 kg',
                'dob' => '1994-08-15',
                'weightGoal' => '55 kg',
                'occupation' => 'Marketing Manager',
                'dietaryPref' => 'Vegetarian',
                'goal' => 'Improve overall fitness',
                'reason' => 'Guidance on achieving weight loss goals',
                'currExerciseRoutine' => '',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Daniel',
                'last_name' => 'Nguyen',
                'email' => 'daniel.nguyen@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '423 987 654',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '456 King Street',
                    'line_two' => '',
                    'city' => 'Melbourne',
                    'zip_code' => '3000',
                    'state' => 'VIC',
                    'country' => 'AU'
                ],
                'age' => 25,
                'height' => '178 cm',
                'weight' => '75 kg',
                'dob' => '1999-05-20',
                'weightGoal' => '70 kg',
                'occupation' => 'Software Developer',
                'dietaryPref' => 'Paleo',
                'goal' => 'Build muscle mass',
                'reason' => 'Customized workout plan',
                'currExerciseRoutine' => 'Weightlifting 3 times a week',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Patel',
                'email' => 'emily.patel@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '433 123 456',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '789 Queen Street',
                    'line_two' => '',
                    'city' => 'Brisbane',
                    'zip_code' => '4000',
                    'state' => 'QLD',
                    'country' => 'AU'
                ],
                'age' => 35,
                'height' => '160 cm',
                'weight' => '68 kg',
                'dob' => '1989-11-10',
                'weightGoal' => '60 kg',
                'occupation' => 'Nurse',
                'dietaryPref' => 'Gluten-free',
                'goal' => 'Improve cardiovascular health',
                'reason' => 'Nutritional guidance',
                'currExerciseRoutine' => 'Jogging 3 times a week',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Jack',
                'last_name' => 'Wilson',
                'email' => 'jack.wilson@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '411 555 999',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '101 Collins Street',
                    'line_two' => '',
                    'city' => 'Perth',
                    'zip_code' => '6000',
                    'state' => 'WA',
                    'country' => 'AU'
                ],
                'age' => 28,
                'height' => '185 cm',
                'weight' => '80 kg',
                'dob' => '1996-03-25',
                'weightGoal' => '78 kg',
                'occupation' => 'Financial Analyst',
                'dietaryPref' => 'Mediterranean',
                'goal' => 'Increase flexibility',
                'reason' => 'Rehabilitation after injury',
                'currExerciseRoutine' => 'Yoga twice a week',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Brown',
                'email' => 'sophie.brown@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '422 777 333',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '202 George Street',
                    'line_two' => '',
                    'city' => 'Adelaide',
                    'zip_code' => '5000',
                    'state' => 'SA',
                    'country' => 'AU'
                ],
                'age' => 32,
                'height' => '170 cm',
                'weight' => '65 kg',
                'dob' => '1992-09-08',
                'weightGoal' => '62 kg',
                'occupation' => 'Teacher',
                'dietaryPref' => 'Vegan',
                'goal' => 'Stress management',
                'reason' => 'Meditation guidance',
                'currExerciseRoutine' => 'Pilates once a week',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Liam',
                'last_name' => 'Thompson',
                'email' => 'liam.thompson@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '412 345 987',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '303 York Street',
                    'line_two' => '',
                    'city' => 'Hobart',
                    'zip_code' => '7000',
                    'state' => 'TAS',
                    'country' => 'AU'
                ],
                'age' => 40,
                'height' => '175 cm',
                'weight' => '85 kg',
                'dob' => '1984-07-15',
                'weightGoal' => '80 kg',
                'occupation' => 'Architect',
                'dietaryPref' => 'Low-carb',
                'goal' => 'Increase energy levels',
                'reason' => 'Lifestyle modification',
                'currExerciseRoutine' => 'Cycling on weekends',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Mia',
                'last_name' => 'Rodriguez',
                'email' => 'mia.rodriguez@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '433 888 111',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '505 Elizabeth Street',
                    'line_two' => '',
                    'city' => 'Darwin',
                    'zip_code' => '0800',
                    'state' => 'NT',
                    'country' => 'AU'
                ],
                'age' => 27,
                'height' => '163 cm',
                'weight' => '55 kg',
                'dob' => '1997-12-03',
                'weightGoal' => '52 kg',
                'occupation' => 'Graphic Designer',
                'dietaryPref' => 'Flexitarian',
                'goal' => 'Improve sleep quality',
                'reason' => 'Insomnia management',
                'currExerciseRoutine' => 'Swimming twice a week',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Oliver',
                'last_name' => 'Lee',
                'email' => 'oliver.lee@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '411 222 333',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '707 Lonsdale Street',
                    'line_two' => '',
                    'city' => 'Canberra',
                    'zip_code' => '2600',
                    'state' => 'ACT',
                    'country' => 'AU'
                ],
                'age' => 31,
                'height' => '180 cm',
                'weight' => '70 kg',
                'dob' => '1993-04-18',
                'weightGoal' => '68 kg',
                'occupation' => 'Lawyer',
                'dietaryPref' => 'Vegetarian',
                'goal' => 'Stress reduction',
                'reason' => 'Mindfulness training',
                'currExerciseRoutine' => 'Hiking on weekends',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Isabella',
                'last_name' => 'Evans',
                'email' => 'isabella.evans@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '421 777 888',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '909 Bourke Street',
                    'line_two' => '',
                    'city' => 'Melbourne',
                    'zip_code' => '3000',
                    'state' => 'VIC',
                    'country' => 'AU'
                ],
                'age' => 29,
                'height' => '167 cm',
                'weight' => '60 kg',
                'dob' => '1995-06-28',
                'weightGoal' => '58 kg',
                'occupation' => 'Accountant',
                'dietaryPref' => 'Keto',
                'goal' => 'Increase muscle tone',
                'reason' => 'Strength training program',
                'currExerciseRoutine' => 'CrossFit 4 times a week',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Ethan',
                'last_name' => 'Campbell',
                'email' => 'ethan.campbell@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '400 999 222',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '606 William Street',
                    'line_two' => '',
                    'city' => 'Sydney',
                    'zip_code' => '2000',
                    'state' => 'NSW',
                    'country' => 'AU'
                ],
                'age' => 33,
                'height' => '182 cm',
                'weight' => '90 kg',
                'dob' => '1991-10-05',
                'weightGoal' => '85 kg',
                'occupation' => 'Engineer',
                'dietaryPref' => 'Omnivore',
                'goal' => 'Improve endurance',
                'reason' => 'Marathon training plan',
                'currExerciseRoutine' => 'Running 5 times a week',
                'medicalInfo' => 'None'
            ],
            [
                'first_name' => 'Ava',
                'last_name' => 'White',
                'email' => 'ava.white@example.com',
                'password' => 'Password01',
                'roles' => [User::ROLES_ALLOWED[User::ROLE_CUSTOMER]],
                'phone' => [
                    'prefix' => '+61',
                    'number' => '422 666 777',
                    'flag' => '/build/images/flags/au.svg'
                ],
                'address' => [
                    'line_one' => '808 Pitt Street',
                    'line_two' => '',
                    'city' => 'Brisbane',
                    'zip_code' => '4000',
                    'state' => 'QLD',
                    'country' => 'AU'
                ],
                'age' => 26,
                'height' => '168 cm',
                'weight' => '58 kg',
                'dob' => '1998-02-14',
                'weightGoal' => '55 kg',
                'occupation' => 'Marketing Coordinator',
                'dietaryPref' => 'Pescatarian',
                'goal' => 'Improve flexibility',
                'reason' => 'Yoga instructor guidance',
                'currExerciseRoutine' => 'Yoga classes twice a week',
                'medicalInfo' => 'None'
            ],
        ];

        foreach ($customers as $customer) {
            $user = new User();
            $user->setFirstName($customer['first_name']);
            $user->setLastName($customer['last_name']);
            $user->setPassword($this->hasher->hashPassword($user, $customer['password']));
            $user->setEmail($customer['email']);
            $user->setUsername($customer['email']);
            $user->setRoles($customer['roles']);
            $phone = new Phone();
            $phone->setPrefix($customer['phone']['prefix']);
            $phone->setNumber($customer['phone']['number']);
            $phone->setFlag($customer['phone']['flag']);
            $user->addPhone($phone);
            $address = new Address();
            $address->setLineOne($customer['address']['line_one']);
            $address->setLineTwo($customer['address']['line_two']);
            $address->setCity($customer['address']['city']);
            $address->setZipCode($customer['address']['zip_code']);
            $address->setState($customer['address']['state']);
            $address->setCountry($customer['address']['country']);
            $user->setAddress($address);
            // Customer data
            $newCustomer = new Customer();
            $newCustomer->setAge($customer['age']);
            $newCustomer->setHeight($customer['height']);
            $newCustomer->setWeight($customer['weight']);
            $newCustomer->setDob($customer['dob']);
            $newCustomer->setGoalWeight($customer['weightGoal']);
            $newCustomer->setOccupation($customer['occupation']);
            $newCustomer->setDietaryPreference($customer['dietaryPref']);
            $newCustomer->setGoals($customer['goal']);
            $newCustomer->setReasonSeekProfessional($customer['reason']);
            $newCustomer->setCurrExerciseRoutine($customer['currExerciseRoutine']);
            $newCustomer->setMedicalInfo($customer['medicalInfo']);

            $user->setCustomer($newCustomer);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['customers'];
    }
}
