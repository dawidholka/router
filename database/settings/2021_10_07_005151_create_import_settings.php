<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateImportSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('import.start_row', 3);
        $this->migrator->add('import.point_name', 'B');
        $this->migrator->add('import.point_street', 'C');
        $this->migrator->add('import.point_building_number', 'D');
        $this->migrator->add('import.point_apartment', 'E');
        $this->migrator->add('import.point_city', 'G');
        $this->migrator->add('import.point_postcode', 'F');
        $this->migrator->add('import.point_intercom', 'H');
        $this->migrator->add('import.point_phone', 'J');
        $this->migrator->add('import.point_note', 'K');
        $this->migrator->add('import.waypoint_content', 'M O');
        $this->migrator->add('import.waypoint_quantity', 'R');
    }

    public function down(): void
    {
        $this->migrator->delete('import.start_row');
        $this->migrator->delete('import.point_name');
        $this->migrator->delete('import.point_street');
        $this->migrator->delete('import.point_building_number');
        $this->migrator->delete('import.point_apartment');
        $this->migrator->delete('import.point_city');
        $this->migrator->delete('import.point_postcode');
        $this->migrator->delete('import.point_intercom');
        $this->migrator->delete('import.point_phone');
        $this->migrator->delete('import.point_note');
        $this->migrator->delete('import.waypoint_content');
        $this->migrator->delete('import.waypoint_quantity');
    }
}
