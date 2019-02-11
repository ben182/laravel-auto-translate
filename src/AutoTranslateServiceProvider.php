<?php

namespace Ben182\AutoTranslate;

use Themsaid\Langman\Manager;
use Illuminate\Support\ServiceProvider;
use Ben182\AutoTranslate\Commands\AllCommand;
use Ben182\AutoTranslate\Commands\MissingCommand;

class AutoTranslateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-auto-translate');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-auto-translate');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('auto-translate.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-auto-translate'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-auto-translate'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-auto-translate'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                AllCommand::class,
                MissingCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'auto-translate');

        // Register the main class to use with the facade
        $this->app->singleton('auto-translate', function () {

            config([
                'langman.path' => config('auto-translate.path'),
            ]);

            $translator = config('auto-translate.translator');

            return new AutoTranslate(app(Manager::class), new $translator);
        });
    }
}
