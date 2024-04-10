<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

    public function getFilters()
    {
        return array(
            new TwigFilter('normalizeLangToFlag', [$this, 'normalizeLangToFlag']),
            new TwigFilter('convertLanguageCodeToName', [$this, 'convertLanguageCodeToName']),
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
}