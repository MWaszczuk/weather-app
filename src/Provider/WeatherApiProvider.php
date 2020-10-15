<?php

namespace App\Provider;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherApiProvider implements TemperatureProviderInterface
{
    /**
     * @var string
     */
    private $weatherApiKey;

    public function __construct(string $weatherApiKey)
    {
        $this->weatherApiKey = $weatherApiKey;
    }

    public function getTemperatureForCityAndCountry(string $city, string $country): float
    {
        $httpClient = new CurlHttpClient();
        $url = 'https://api.weatherapi.com/v1/current.json?q='.$city.','.$country.'&key='.$this->weatherApiKey;

        try {
            $request = $httpClient->request('GET', $url);
            $content = $request->getContent();
            $json = json_decode($content);

            return $json->current->temp_c;
        } catch (TransportExceptionInterface $e) {

        }

        throw new NotFoundHttpException();
    }
}