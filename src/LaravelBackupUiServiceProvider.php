<?php

namespace XattaTrone\LaravelBackupUi;

use Illuminate\Support\ServiceProvider;

class LaravelBackupUiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'xatta-trone');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'xatta-trone');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-backup-ui.php', 'laravel-backup-ui');

        // Register the service the package provides.
        $this->app->singleton('laravel-backup-ui', function ($app) {
            return new LaravelBackupUi;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-backup-ui'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-backup-ui.php' => config_path('laravel-backup-ui.php'),
        ], 'laravel-backup-ui.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/xatta-trone'),
        ], 'laravel-backup-ui.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/xatta-trone'),
        ], 'laravel-backup-ui.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/xatta-trone'),
        ], 'laravel-backup-ui.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
