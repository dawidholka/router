<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OSRMSettings extends Settings
{
    public ?string $url;
    public bool $is_cloud_run;
    public ?string $google_json_key;

    public static function group(): string
    {
        return 'osrm';
    }

    public static function encrypted(): array
    {
        return [
            'google_json_key'
        ];
    }
}
