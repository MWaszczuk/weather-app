<?php

namespace App\Provider;

class TemperatureProviderFactory implements TemperatureProviderFactoryInterface
{
    /**
     * @var string
     */
    private $openWeatherMapApiKey;

    private $weatherApiKey;

    public function __construct(string $openWeatherMapApiKey, string $weatherApiKey)
    {
        $this->openWeatherMapApiKey = $openWeatherMapApiKey;
        $this->weatherApiKey = $weatherApiKey;
    }

    public function createOpenWeatherMapProvider(): OpenWeatherMapProvider
    {
        return new OpenWeatherMapProvider($this->openWeatherMapApiKey);
    }

    public function createWeatherApiProvider(): WeatherApiProvider
    {
        return new WeatherApiProvider($this->weatherApiKey);
    }
}