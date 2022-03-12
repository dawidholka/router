<?php

namespace Router\Optimization\Infrastructure\Services;

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

        if ($response->failed()) {
            throw new \Exception('Api returned: ' . $response->json()['error'] ?? $response->status());
        }

        return $response->json();
    }

    public function geocodeSearchStructured()
    {
        //TODO
    }
}
