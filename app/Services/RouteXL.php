<?php

namespace App\Services;

use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Http;

class RouteXL
{
    private const TOUR_URL = 'https://api.routexl.com/tour/';

    private ?string $username;
    private ?string $password;

    public function __construct(GeneralSettings $settings)
    {
        $this->username = $settings->routexl_username;
        $this->password = $settings->routexl_password;
    }

    public function optimize(array $locations)
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->asForm()
            ->post(self::TOUR_URL, [
                'locations' => $locations
            ]);

        if($response->failed()){
            throw new \Exception('Api returned code: '.$response->status());
        }

        return $response->json();
    }


}
