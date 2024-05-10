<?php

namespace App\Twig;

use App\Entity\MealPlan;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    public function getFilters()
    {
        return array(
            new TwigFilter('normalizeLangToFlag', [$this, 'normalizeLangToFlag']),
            new TwigFilter('convertLanguageCodeToName', [$this, 'convertLanguageCodeToName']),
            new TwigFilter('convertToWeekNames', [$this, 'convertToWeekNames']),
        );
    }

    public function normalizeLangToFlag($languageCode)
    {
        if ($languageCode === "en" or strtolower($languageCode) === "en-us") {
            return "us.svg";
        } elseif (strtolower($languageCode) === "en-gb") {
            return "gb.svg";
        } else {
            return $languageCode . ".svg";
        }
    }

    public function convertLanguageCodeToName($languageCode)
    {
        if ($languageCode == 'en') {
            return "English";
        } elseif ($languageCode == 'fr') {
            return "French";
        } elseif ($languageCode == 'pt') {
            return "Portuguese";
        } else {
            return $languageCode;
        }
    }

    public function convertToWeekNames(array|null $days)
    {
        if ($days === null) {
            return null;
        }
        $weekdays = [];
        sort($days);
        foreach ($days as $day) {
            $weekdays[] = $this->translator->trans(MealPlan::DAYS[$day], [], 'form');
        }

        return $weekdays;
    }
}