<?php

namespace Spatie\LaravelSettings;

use Illuminate\Database\Events\SchemaLoaded;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelSettings\Console\CacheDiscoveredSettingsCommand;
use Spatie\LaravelSettings\Console\ClearCachedSettingsCommand;
use Spatie\LaravelSettings\Console\ClearDiscoveredSettingsCacheCommand;
use Spatie\LaravelSettings\Console\MakeSettingsMigrationCommand;
use Spatie\LaravelSettings\Factories\SettingsRepositoryFactory;
use Spatie\LaravelSettings\Migrations\SettingsMigration;
use Spatie\LaravelSettings\SettingsRepositories\SettingsRepository;
use SplFileInfo;

class LaravelSettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/settings.php' => config_path('settings.php'),
            ], 'settings');

            if (! class_exists('CreateSettingsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_settings_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_settings_table.php'),
                ], 'migrations');
            }

            $this->commands([
                MakeSettingsMigrationCommand::class,
                CacheDiscoveredSettingsCommand::class,
                ClearDiscoveredSettingsCacheCommand::class,
                ClearCachedSettingsCommand::class,
            ]);
        }

        Event::subscribe(SettingsEventSubscriber::class);
        Event::listen(SchemaLoaded::class, fn ($event) => $this->removeMigrationsWhenSchemaLoaded($event));

        $this->loadMigrationsFrom(config('settings.migrations_path'));
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'settings');

        $this->app->bind(SettingsRepository::class, fn () => SettingsRepositoryFactory::create());

        $this->app->bind(SettingsCache::class, fn () => new SettingsCache(
            config('settings.cache.enabled', false),
            config('settings.cache.store'),
            config('settings.cache.prefix')
        ));

        $this->app->singleton(SettingsMapper::class);

        $settingsContainer = app(SettingsContainer::class);
        $settingsContainer->registerBindings();
    }

    private function removeMigrationsWhenSchemaLoaded(SchemaLoaded $event)
    {
        $migrations = collect(app(Filesystem::class)->files(config('settings.migrations_path')))
            ->mapWithKeys(function (SplFileInfo $file) {
                preg_match('/class\s*(\w*)\s*extends/', file_get_contents($file->getRealPath()), $found);

                if (empty($found)) {
                    return null;
                }

                require_once $file->getRealPath();

                return [$file->getBasename('.php') => $found[1]];
            })
            ->filter(fn (string $migrationClass) => is_subclass_of($migrationClass, SettingsMigration::class))
            ->keys();

        $event->connection
            ->table(config()->get('database.migrations'))
            ->useWritePdo()
            ->whereIn('migration', $migrations)
            ->delete();
    }
}
