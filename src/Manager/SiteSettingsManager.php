<?php

namespace App\Manager;

use App\Repository\SettingsRepository;

class SiteSettingsManager
{
    private SettingsRepository $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function get()
    {
        return $this->settingsRepository->findOneBy([]);
    }
}