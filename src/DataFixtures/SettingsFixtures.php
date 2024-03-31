<?php

namespace App\DataFixtures;

use App\Entity\Settings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SettingsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $settings = new Settings();
        $settings->setCreatedAt(new \DateTime());
        $settings->setUpdatedAt(new \DateTime());
        $settings->setName("Terra Nutri");
        $settings->setDisplayName(true);
        $manager->persist($settings);
        $manager->flush();
    }
}
