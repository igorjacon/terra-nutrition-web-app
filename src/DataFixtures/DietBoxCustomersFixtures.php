<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\Phone;
use App\Entity\User;
use App\Repository\ProfessionalRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;

class DietBoxCustomersFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private ProfessionalRepository $professionalRepository){}

    public function load(ObjectManager $manager)
    {
        //load the CSV document from a file path
        $csv = Reader::createFromPath('clients.csv', 'r');
        $csv->setHeaderOffset(0);

        //returns all the records as
        $records = $csv->getRecords();

        foreach ($records as $customer) {
            $fullName = explode(" ", $customer['name'], 2);
            $firstName = $fullName[0];
            $lastName = $fullName[1];
            $createdAt = DateTime::createFromFormat('d/m/Y', trim($customer['created_at']));
            $dobDate = DateTime::createFromFormat('d/m/Y', trim($customer['dob']));
            $dobYear = $dobDate->format('Y');

            $user = new User();
            if ($createdAt) {
                $user->setCreatedAt($createdAt);
            }
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($customer['email'] !== "" ? $customer['email'] : null);
            if ($customer['email'] !== "") {
                $user->setUsername($customer['email']);
            } else {
                $user->setUsername(strtolower($firstName) . $dobYear);
            }
            $phone = new Phone();
            $phoneNumber = $customer['cell'] !== "" ? $customer['cell'] : $customer['phone'];
            $formattedPhoneNumber = $this->extractPrefixAndNumber($phoneNumber);
            $phone->setPrefix($formattedPhoneNumber['prefix']);
            $phone->setNumber($formattedPhoneNumber['number']);
            if ($formattedPhoneNumber['prefix'] == "+55") {
                $phone->setFlag('br');
            } elseif ($formattedPhoneNumber['prefix'] == "+61") {
                $phone->setFlag('au');
            } else {
                $phone->setFlag('au');
            }
            $user->addPhone($phone);

            $address = new Address();
            $address->setLineOne($customer['address']);
            $user->setAddress($address);

            // Customer data
            $newCustomer = new Customer();
            if ($createdAt) {
                $newCustomer->setCreatedAt($createdAt);
            }
            if ($dobDate) {
                $newCustomer->setDob($dobDate);
            }
            $newCustomer->setOccupation($customer['occupation']);
            $user->addRole(User::ROLES_ALLOWED[User::ROLE_CUSTOMER]);

            // Assign customer to Camila
            $firstProfessional = $this->professionalRepository->findOneBy([]);
            if ($firstProfessional) {
                $firstProfessional->addCustomer($newCustomer);
            }

            $user->setCustomer($newCustomer);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dietbox-clients'];
    }

    private function extractPrefixAndNumber(mixed $phone)
    {
        if (preg_match('/^(\+\d{1,2})(\d+)$/', $phone, $matches)) {
            return ['prefix' => $matches[1], 'number' => $matches[2]];
        }
        return ['prefix' => '', 'number' => $phone]; // No prefix found
    }
}