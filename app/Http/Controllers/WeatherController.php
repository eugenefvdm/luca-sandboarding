<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct(
        private WeatherService $weatherService
    ) {}

    public function current(Request $request)
    {
        $city = $request->get('city', 'Cape Town');
        
        $weatherData = $this->weatherService->getCurrentWeather($city);
        
        if (!$weatherData) {
            return response()->json(['error' => 'Unable to fetch weather data'], 500);
        }

        $formattedData = $this->weatherService->formatWeatherData($weatherData);
        
        return response()->json($formattedData);
    }
}
