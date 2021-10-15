<?php

namespace App\Services;

use App\DTOs\GeocodeData;
use App\Models\Point;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GoogleMaps
{
    protected const GEOCODE_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    protected string $apiKey;

    public function __construct(GeneralSettings $settings)
    {
        $this->apiKey = $settings->google_maps_api_key;
    }

    protected function buildUrl(string $url, array $query): string
    {
        return $url . '?' . http_build_query($query);
    }

    /**
     * @throws Exception
     */
    public function geocode(string $fullAddress): GeocodeData
    {
        $url = $this->buildUrl(self::GEOCODE_URL, [
            'address' => $fullAddress,
            'key' => $this->apiKey
        ]);

        $response = Http::get($url);

        return $this->parseGecodeResponse($response);
    }

    public function bulkGeocode(Collection $points): array
    {
        $urls = $points->map(function (Point $point) {
            return $this->buildUrl(self::GEOCODE_URL, [
                'address' => $point->address,
                'key' => $this->apiKey
            ]);
        });

        $responses = Http::pool(function (Pool $pool) use ($urls) {
            return $urls->map(function (string $url) use ($pool) {
                return $pool->get($url);
            });
        });

        $geocodedData = [];

        foreach ($responses as $response) {
            try {
                $geocodedData[] = $this->parseGecodeResponse($response);
            } catch (Exception) {
                $geocodedData[] = null;
            }
        }

        return $geocodedData;
    }

    private function parseGecodeResponse(Response $response): GeocodeData
    {
        if ($response->failed()) {
            throw new Exception('Error');
        }

        $data = $response->json();

        if ($data['status'] !== 'OK') {
            throw new Exception($data['error_message'] ?? 'Błąd');
        }

        if (!isset(
            $data['results'][0]['geometry']['location']['lat'],
            $data['results'][0]['geometry']['location']['lng'],
            $data['results'][0]['formatted_address']
        )) {
            throw new Exception('Error');
        }

        return new GeocodeData([
            'lat' => $data['results'][0]['geometry']['location']['lat'],
            'lng' => $data['results'][0]['geometry']['location']['lng'],
            'address' => $data['results'][0]['formatted_address']
        ]);
    }
}
