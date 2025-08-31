<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.api_key');
    }

    public function getCurrentWeather(string $city): ?array
    {
        $cacheKey = "weather_{$city}";
        
        // Cache for 10 minutes to avoid excessive API calls
        return Cache::remember($cacheKey, 600, function () use ($city) {
            try {
                $response = Http::get("{$this->baseUrl}/weather", [
                    'q' => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric'
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return null;
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    public function formatWeatherData(array $weatherData): array
    {
        return [
            'temperature' => round($weatherData['main']['temp']),
            'feels_like' => round($weatherData['main']['feels_like']),
            'description' => ucfirst($weatherData['weather'][0]['description']),
            'humidity' => $weatherData['main']['humidity'],
            'wind_speed' => round($weatherData['wind']['speed'] * 3.6, 1), // Convert m/s to km/h
            'icon' => $weatherData['weather'][0]['icon'],
            'city' => $weatherData['name'],
            'country' => $weatherData['sys']['country']
        ];
    }
}
