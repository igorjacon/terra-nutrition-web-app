<?php

namespace App\DataFixtures;

use App\Entity\FoodMeasurement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class FoodMeasurementFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $measurements = [
            ['name' => 'grams', 'abbr' => 'g', 'quantity' => 100],
            ['name' => 'kilograms', 'abbr' => 'kg', 'quantity' => 1000],
            ['name' => 'miligrams', 'abbr' => 'mg', 'quantity' => 1],
            ['name' => 'liters', 'abbr' => 'L', 'quantity' => 1000],
            ['name' => 'mililiters', 'abbr' => 'mL', 'quantity' => 100],
            ['name' => 'unit(s)', 'abbr' => null, 'quantity' => 100],
            ['name' => 'cup', 'abbr' => null, 'quantity' => 150],
        ];

        foreach ($measurements as $data) {
            $measurement = new FoodMeasurement();
            $measurement->setName($data['name']);
            $measurement->setAbbreviation($data['abbr']);
            $measurement->setGramQuantity($data['quantity']);
            $manager->persist($measurement);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['food_measurements'];
    }
}
