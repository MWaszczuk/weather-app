<?php

namespace App\Manager;

use App\DTO\ForecastDTO;
use App\Entity\Forecast;
use Doctrine\ORM\EntityManagerInterface;

class ForecastManager
{
    /**
     * @var Forecast
     */
    private $forecast;

    /**
     * @var array
     */
    private $temperatures;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->forecast = new Forecast();
        $this->entityManager = $entityManager;
    }

    public function assignValuesFromDto(ForecastDTO $forecastDto): void
    {
        $this->forecast->setCity($forecastDto->city);
        $this->forecast->setCountry($forecastDto->country);
    }

    public function addTemperature(float $temperature): void
    {
        $this->temperatures[] = $temperature;
    }

    public function store(): void
    {
        $this->calculateAverageTemperature();

        $this->entityManager->persist($this->forecast);
        $this->entityManager->flush();
    }

    private function calculateAverageTemperature(): void
    {
        $this->forecast->setTemperature(array_sum($this->temperatures) / count ($this->temperatures));
    }
}