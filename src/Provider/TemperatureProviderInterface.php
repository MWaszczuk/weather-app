<?php

namespace App\Provider;

interface TemperatureProviderInterface
{
    public function getTemperatureForCityAndCountry(string $city, string $country): float;
}