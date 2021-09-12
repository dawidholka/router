<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.force_photo_upload', false);
    }

    public function down(): void
    {
        $this->migrator->delete('general.force_photo_upload');
    }
}
