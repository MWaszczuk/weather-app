<?php

namespace App\Controller;

use App\DTO\ForecastDTO;
use App\Form\ForecastType;
use App\Manager\ForecastManager;
use App\Provider\TemperatureProviderFactoryInterface;
use App\Repository\ForecastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param TemperatureProviderFactoryInterface $temperatureProviderFactory
     * @param ForecastManager $forecastManager
     * @param ForecastRepository $forecastRepository
     * @return Response
     */
    public function index(Request $request,
        TemperatureProviderFactoryInterface $temperatureProviderFactory,
        ForecastManager $forecastManager,
        ForecastRepository $forecastRepository)
    {
        $forecastDto = new ForecastDTO();
        $forecastForm = $this->createForm(ForecastType::class, $forecastDto);

        $forecastForm->handleRequest($request);
        if ($forecastForm->isSubmitted() && $forecastForm->isValid()) {
            $openWeatherProvider = $temperatureProviderFactory->createOpenWeatherMapProvider();
            $weatherApiProvider = $temperatureProviderFactory->createWeatherApiProvider();

            try {
                $openWeatherTemperature = $openWeatherProvider->getTemperatureForCityAndCountry($forecastDto->city, $forecastDto->country);
                $weatherApiTemperature = $weatherApiProvider->getTemperatureForCityAndCountry($forecastDto->city, $forecastDto->country);

                $forecastManager->assignValuesFromDto($forecastDto);
                $forecastManager->addTemperature($openWeatherTemperature);
                $forecastManager->addTemperature($weatherApiTemperature);
                $forecastManager->store();

                $this->addFlash('success', 'Znaleziona temperatura została zapisana.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Temperatura dla danej lokalizacji nie mogła zostać znaleziona.');
            }
        }

        $forecasts = $forecastRepository->findAll();

        return $this->render('index.html.twig', [
            'forecastForm' => $forecastForm->createView(),
            'forecasts' => $forecasts,
        ]);
    }
}
