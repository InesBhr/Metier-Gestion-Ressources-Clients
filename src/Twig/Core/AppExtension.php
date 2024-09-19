<?php

namespace App\Twig\Core;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_env', [$this, 'getEnvironmentVariable']),
            new TwigFunction('date_fr', [$this, 'getFrenchDate']),
        ];
    }

    /**
     * Return the value of the requested environment variable.
     *
     * @param String $varname
     *
     * @return String
     */
    public function getEnvironmentVariable(string $varname): string
    {
        return $_ENV[$varname];
    }

    /**
     * Convert datetime to french date
     *
     * @param DateTime $date
     * @param string   $format https://www.php.net/manual/fr/function.strftime.php
     *
     * @return string
     */
    public function getFrenchDate(Datetime $date, string $format = "%d %B %Y, %H:%M"): string
    {
        setlocale(LC_TIME, 'fr_FR', 'fra');
        date_default_timezone_set("Europe/Paris");
        return utf8_encode(ucwords(strftime($format, $date->getTimestamp())));
    }
}
