<?php

namespace App\Services;

use App\DTOs\GeocodeData;
use Exception;
use Illuminate\Support\Facades\Http;

class GoogleMaps
{
    protected const GEOCODE_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = 'AIzaSyBw4Uu9vk6g98EWxke4l9IIR_-AD1DHNrQ';
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

        if ($response->failed()) {
            throw new Exception('Error');
        }

        $data = $response->json();

        if ($data['status'] !== 'OK') {
            throw new Exception('Error');
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
