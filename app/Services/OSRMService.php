<?php

namespace App\Services;

use App\Settings\OSRMSettings;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Collection;

class OSRMService
{
    private Client $client;

    public function __construct(private OSRMSettings $settings)
    {
        if ($this->settings->is_cloud_run) {
            $stack = HandlerStack::create();
            $jsonKey = json_decode($this->settings->google_json_key, true);
            $targetAudience = $this->settings->url;
            $credentials = new ServiceAccountCredentials(null, $jsonKey, null, $targetAudience);
            $middleware = new AuthTokenMiddleware($credentials);
            $stack->push($middleware);
            $this->client = new Client([
                'handler' => $stack,
                'auth' => 'google_auth',
                'base_uri' => $this->settings->url
            ]);
        } else {
            $this->client = new Client([
                'base_uri' => $this->settings->url
            ]);
        }
    }

    public function trip(Collection $locations, bool $roundTrip, string $source, string $destination)
    {
        $locationStrings = $locations->pluck('location');
        $coordinates = implode(';', $locationStrings->toArray());

        $uri = '/trip/v1/car/' . $coordinates . '?roundtrip=' . json_encode($roundTrip) . '&source=' . $source . '&destination=' . $destination;

        $response = $this->client->get($uri);

        return json_decode($response->getBody());
    }

}
