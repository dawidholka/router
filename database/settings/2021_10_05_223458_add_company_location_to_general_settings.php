<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddCompanyLocationToGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.company_lat', null);
        $this->migrator->add('general.company_lng', null);
    }

    public function down(): void
    {
        $this->migrator->delete('general.company_lat');
        $this->migrator->delete('general.company_lng');
    }
}
