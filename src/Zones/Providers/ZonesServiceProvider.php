<?php

namespace Router\Zones\Providers;

use Illuminate\Support\ServiceProvider;

class ZonesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Database/Migrations');
    }
}
