<?php

namespace App\DataFixtures;

use App\Entity\MealType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class MealTypeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $mealTypes = [
            ['name' => 'breakfast', 'order' => 1],
            ['name' => 'lunch', 'order' => 2],
            ['name' => 'dinner', 'order' => 3],
            ['name' => 'snacks', 'order' => 4],
        ];

        foreach ($mealTypes as $data) {
            $mealType = new MealType();
            $mealType->setName($data['name']);
            $mealType->setPosition($data['order']);
            $manager->persist($mealType);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['meal_type'];
    }
}
