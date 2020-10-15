<?php

namespace App\Provider;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OpenWeatherMapProvider implements TemperatureProviderInterface
{
    /**
     * @var string
     */
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getTemperatureForCityAndCountry(string $city, string $country): float
    {
        $httpClient = new CurlHttpClient();
        $url = 'https://api.openweathermap.org/data/2.5/weather?q='.$city.','.$country.'&units=metric&lang=pl&appid='.$this->apiKey;

        try {
            $request = $httpClient->request('GET', $url);
            $content = $request->getContent();
            $json = json_decode($content);

            return $json->main->temp;

        } catch (TransportExceptionInterface $e) {

        }

        throw new NotFoundHttpException();
    }
}