<?php

declare(strict_types=1);

namespace App\Common\Core\Service\Intl;

use Symfony\Component\Intl\Countries as IntlCountries;
use Symfony\Component\Intl\Locales;

final class Countries
{
    /** @var array<string, array<string>> */
    private const array CHARACTER_REMAPPING = [
        'ç' => ['c'],
        'Ç' => ['C'],
        'ñ' => ['n'],
        'Ñ' => ['N'],
        'ü' => ['u'],
        'Ü' => ['U'],
        'ö' => ['o'],
        'Ö' => ['O'],
        'ä' => ['a'],
        'Ä' => ['A'],
        'ß' => ['ss', 'uss'],
        'á' => ['a'],
        'Á' => ['A'],
        'é' => ['e'],
        'É' => ['E'],
        'í' => ['i'],
        'Í' => ['I'],
        'ó' => ['o'],
        'Ó' => ['O'],
        'ú' => ['u'],
        'Ú' => ['U'],
    ];

    /** @var array<string, string> */
    private const array COUNTRY_ALIASES = [
        'USA' => 'US',
        'United States of America' => 'US',
        'UK' => 'GB',
        'Great Britain' => 'GB',
        'England' => 'GB',
        'Holland' => 'NL',
        'The Netherlands' => 'NL',
    ];

    public static function findCountryCode(string $countryName): ?string
    {
        if ('' === $countryName) {
            return null;
        }

        if (in_array($countryName, IntlCountries::getCountryCodes(), true)) {
            return $countryName;
        }

        $normalizedName = trim($countryName);
        foreach (self::COUNTRY_ALIASES as $alias => $code) {
            if (strcasecmp($alias, $normalizedName) === 0) {
                return $code;
            }
        }

        $variations = self::generateRemappedVariations($normalizedName);

        foreach ($variations as $variation) {
            $foundCode = self::searchInAllLocales($variation);
            if (null !== $foundCode) {
                return $foundCode;
            }
        }

        return null;
    }

    public static function findCountryName(string $countryCode): ?string
    {
        if ('' === $countryCode) {
            return null;
        }

        if (!IntlCountries::exists($countryCode)) {
            return null;
        }

        return IntlCountries::getName($countryCode, 'en');
    }

    public static function getEnglishName(string $countryCode): ?string
    {
        if (!IntlCountries::exists($countryCode)) {
            return null;
        }

        return IntlCountries::getName($countryCode, 'en');
    }

    public static function normalizeToEnglish(?string $countryName): ?string
    {
        if (null === $countryName || '' === $countryName) {
            return null;
        }

        $countryCode = self::findCountryCode($countryName);

        if (null === $countryCode) {
            return $countryName;
        }

        return self::getEnglishName($countryCode);
    }

    /** @return array<string> */
    private static function generateRemappedVariations(string $countryName): array
    {
        $variations = [$countryName];

        foreach (self::CHARACTER_REMAPPING as $specialChar => $replacements) {
            if (!str_contains($countryName, $specialChar)) {
                continue;
            }

            foreach ($replacements as $replacement) {
                $variations[] = str_replace($specialChar, $replacement, $countryName);
            }
        }

        return array_unique($variations);
    }

    private static function searchInAllLocales(string $countryName): ?string
    {
        foreach (Locales::getLocales() as $locale) {
            $code = array_search($countryName, IntlCountries::getNames($locale), true);
            if (false !== $code) {
                return $code;
            }
        }

        $lowerCountryName = mb_strtolower($countryName);

        foreach (Locales::getLocales() as $locale) {
            foreach (IntlCountries::getNames($locale) as $code => $name) {
                if (mb_strtolower($name) === $lowerCountryName) {
                    return $code;
                }
            }
        }

        return null;
    }
}
