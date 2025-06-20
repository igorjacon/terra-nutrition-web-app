<?php

namespace App\Manager;

use App\Entity\Customer;
use App\Entity\CustomerMeasurement;
use DateTime;

class CustomerManager
{
    public function recommendedBFP(?CustomerMeasurement $customerMeasurement)
    {
        if (!$customerMeasurement) return "-";
        $customer = $customerMeasurement->getCustomer();
        $range = $this->recommendedBfpRange($customer);
        if ($range === null) return "-";

        return "{$range[0]}% - {$range[1]}%";
    }
    public function recommendedLFP(?CustomerMeasurement $customerMeasurement)
    {
        if (!$customerMeasurement) return "-";
        $customer = $customerMeasurement->getCustomer();
        $range = $this->recommendedBfpRange($customer);
        if ($range === null) return "-";

        $min = 100-$range[1];
        $max = 100-$range[0];
        return "{$min}% - {$max}%";
    }

    public function recommendedBf(?CustomerMeasurement $customerMeasurement)
    {
        if (!$customerMeasurement) return "-";
        $customer = $customerMeasurement->getCustomer();
        $range = $this->recommendedBfpRange($customer);
        if ($range === null) return "-";

        $weightKgs = $this->getWeightInKg($customerMeasurement->getCurrWeight() ?? $customer->getWeight());
        if ($weightKgs) {
            $min = ($range[0]/100) * $weightKgs;
            $max = ($range[1]/100) * $weightKgs;
            return "{$min} kg - {$max} kg";
        } else {
            return "-";
        }
    }

    public function recommendedLm(?CustomerMeasurement $customerMeasurement)
    {
        if (!$customerMeasurement) return "-";
        $customer = $customerMeasurement->getCustomer();
        $range = $this->recommendedBfpRange($customer);
        if ($range === null) return "-";

        $weightKgs = $this->getWeightInKg($customerMeasurement->getCurrWeight() ?? $customer->getWeight());
        if ($weightKgs) {
            $min = $weightKgs - (($range[1]/100) * $weightKgs);
            $max = $weightKgs - (($range[0]/100) * $weightKgs);

            return "{$min} kg - {$max} kg";
        } else {
            return "-";
        }
    }

    function getWeightInKg($weightString) {
        // Use regex to match weight followed by 'kg'
        if (preg_match('/^([\d.]+)\s*kg$/i', trim($weightString), $matches)) {
            return (float)$matches[1]; // Return the numeric weight as an integer
        }
        return null; // Return null if not in kg
    }

    public function getAge($dob)
    {
        $today = new DateTime();
        $age = $dob->diff($today)->y;
        return $age;
    }

    public function recommendedBfpRange(Customer $customer)
    {
        $gender = $customer->getGender();
        $age = $this->getAge($customer->getDob());
        // Define body fat percentage ranges
        $bodyFatRanges = [
            "male" => [
                [18, 24, 10, 15],
                [25, 34, 12, 18],
                [35, 44, 14, 20],
                [45, 54, 16, 22],
                [55, 64, 18, 24],
                [65, 100, 20, 26], // 65+ category
            ],
            "female" => [
                [18, 24, 18, 22],
                [25, 34, 20, 24],
                [35, 44, 22, 26],
                [45, 54, 24, 28],
                [55, 64, 26, 30],
                [65, 100, 28, 32], // 65+ category
            ]
        ];

        // Validate gender
        if (!isset($bodyFatRanges[$gender])) {
            return null;
        }

        // Find the appropriate range based on age
        foreach ($bodyFatRanges[$gender] as $range) {
            if ($age >= $range[0] and $age <= $range[1]) {
                return [$range[2], $range[3]];
            }
        }

        return null;
    }
}