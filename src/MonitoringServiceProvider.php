<?php

namespace SaeedVaziry\Monitoring;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use SaeedVaziry\Monitoring\Commands\PurgeCommand;
use SaeedVaziry\Monitoring\Commands\RecordCommand;

class MonitoringServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // facade
        $this->app->bind('monitoring', function () {
            return new Monitoring();
        });

        // merge config file
        $this->mergeConfigFrom(__DIR__ . '/../config/monitoring.php', 'monitoring');

        // register command
        $this->app->singleton('command.monitoring.record', function () {
            return new RecordCommand();
        });
        $this->app->singleton('command.monitoring.purge', function () {
            return new PurgeCommand();
        });

        // publish config
        $this->publishes([
            __DIR__ . '/../config/monitoring.php' => config_path('monitoring.php')
        ], ['monitoring-config', 'laravel-config']);

        // publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], ['monitoring-migrations', 'laravel-migrations']);

        // publish assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/monitoring'),
        ], ['monitoring-assets', 'laravel-assets']);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // load migrations
        if (config('monitoring.migrations', true) && $this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
        }

        // register command
        $this->commands(RecordCommand::class);
        $this->commands(PurgeCommand::class);

        // register routes
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        // register views
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'monitoring');

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $frequency = config('monitoring.frequency');
            $schedule->command('monitoring:record')->$frequency();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.monitoring.record',
            'command.monitoring.purge',
        ];
    }
}
