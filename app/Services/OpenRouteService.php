<?php

namespace App\Services;

use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Http;

class OpenRouteService
{
    private const URL = 'https://api.openrouteservice.org/';

    public function __construct(GeneralSettings $settings)
    {
        $this->apiKey = $settings->open_route_service_api_key;
    }

    public function optimization(array $jobs, array $vehicles)
    {
        $url = self::URL . 'optimization';

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey
        ])->post($url, [
            'jobs' => $jobs,
            'vehicles' => $vehicles
        ]);

        dd(json_encode(['jobs' => $jobs,
            'vehicles' => $vehicles]));

        dd($response->json());

        if ($response->failed()) {
            throw new \Exception('Optimize Error');
        }

        return $response->json();
    }

    public function geocodeSearchStructured()
    {
        //TODO
    }
}
