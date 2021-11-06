<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateOSRMSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('osrm.url', '');
        $this->migrator->add('osrm.is_cloud_run', false);
        $this->migrator->addEncrypted('osrm.google_json_key', null);
    }

    public function down(): void
    {
        $this->migrator->delete('osrm.url');
        $this->migrator->delete('osrm.is_cloud_run');
        $this->migrator->delete('osrm.google_json_key');
    }
}
