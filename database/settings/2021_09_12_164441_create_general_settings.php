<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.force_photo_upload', false);
        $this->migrator->add('general.google_maps_api_key', '');
        $this->migrator->add('general.routexl_username', '');
        $this->migrator->add('general.routexl_password', '');
        $this->migrator->add('general.open_route_service_api_key', '');
    }

    public function down(): void
    {
        $this->migrator->delete('general.force_photo_upload');
        $this->migrator->delete('general.google_maps_api_key');
        $this->migrator->delete('general.routexl_username');
        $this->migrator->delete('general.routexl_password');
        $this->migrator->delete('general.open_route_service_api_key');
    }
}
