<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole;

use ArtisanToolbox\DumpToConsole\Console\Commands\DumpListenCommand;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\DevCommands;
use Illuminate\Support\Benchmark;
use Illuminate\Support\ServiceProvider;

class DumpToConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dump-to-console.php', 'dump-to-console');

        $this->app->singleton(DumpClient::class, function (Application $app): DumpClient {
            $host = $app->make(Repository::class)->get('dump-to-console.host');

            return new DumpClient(is_string($host) && $host !== '' ? $host : DumpClient::DEFAULT_HOST);
        });

        $this->app->singleton(DumpToConsole::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Benchmark::macro('dc', function (callable $callback): mixed {
            return resolve(DumpToConsole::class)->benchmark($callback);
        });

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/dump-to-console.php' => config_path('dump-to-console.php'),
        ], ['dump-to-console', 'dump-to-console-config']);

        $this->commands([
            DumpListenCommand::class,
        ]);

        if ($this->registerDevCommand()) {
            DevCommands::artisan('dump:listen', 'dumps');
        }
    }

    private function registerDevCommand(): bool
    {
        return $this->app->make(Repository::class)->get('dump-to-console.register_dev_command', true) === true;
    }
}
