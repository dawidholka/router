<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public bool $force_photo_upload;

    public ?string $google_maps_api_key;

    public ?string $routexl_username;

    public ?string $routexl_password;

    public ?string $open_route_service_api_key;

    public static function group(): string
    {
        return 'general';
    }
}
