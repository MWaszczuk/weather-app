<?php

namespace App\Provider;

interface TemperatureProviderFactoryInterface
{
    public function createOpenWeatherMapProvider(): OpenWeatherMapProvider;
    public function createWeatherApiProvider(): WeatherApiProvider;
}