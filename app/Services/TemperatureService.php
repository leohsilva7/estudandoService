<?php

namespace App\Services;

class TemperatureService
{
    public function toCelsius(string $fahrenheit):float
    {
        $stringToFloat = floatval($fahrenheit);
        $convertedValue = ($stringToFloat - 32) * 5 / 9;
        return $convertedValue;

    }
}
