<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole;

use ArtisanToolbox\DumpToConsole\Console\Commands\DumpToConsoleCommand;
use Illuminate\Support\ServiceProvider;

class DumpToConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dump-to-console.php', 'dump-to-console');

        $this->app->singleton(DumpToConsole::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/dump-to-console.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dump-to-console');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'dump-to-console');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/dump-to-console.php' => config_path('dump-to-console.php'),
        ], ['dump-to-console', 'dump-to-console-config']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/dump-to-console'),
        ], ['dump-to-console', 'dump-to-console-views']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/dump-to-console'),
        ], ['dump-to-console', 'dump-to-console-lang']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/dump-to-console'),
        ], ['dump-to-console', 'dump-to-console-assets']);

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['dump-to-console', 'dump-to-console-migrations']);

        $this->commands([
            DumpToConsoleCommand::class,
        ]);
    }
}
