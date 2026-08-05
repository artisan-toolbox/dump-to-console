<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole\Console\Commands;

use ArtisanToolbox\DumpToConsole\Console\CliDumper;
use ArtisanToolbox\DumpToConsole\DumpClient;
use ArtisanToolbox\DumpToConsole\DumpRenderer;
use ArtisanToolbox\DumpToConsole\DumpServer;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Symfony\Component\VarDumper\Cloner\Data;

class DumpListenCommand extends Command
{
    protected $signature = 'dump:listen {--host= : Override the configured TCP listener host}';

    protected $description = 'Listen for application dumps';

    public function handle(): int
    {
        $server = $this->server();
        $renderer = $this->renderer();

        $server->start();

        $this->components->info("Listening for dumps on [{$server->host()}].");

        $server->listen(function (Data $data, array $context) use ($renderer): void {
            $renderer->render($data, $context);
        });

        return self::SUCCESS;
    }

    protected function server(): DumpServer
    {
        $host = $this->option('host');

        return new DumpServer(is_string($host) && $host !== '' ? $host : $this->configuredHost());
    }

    protected function renderer(): DumpRenderer
    {
        $compiledViewPath = $this->laravel->make(Repository::class)->get('view.compiled');

        return new DumpRenderer(
            new CliDumper(
                $this->output,
                $this->laravel->basePath(),
                is_string($compiledViewPath) ? $compiledViewPath : '',
            ),
            $this->laravel->basePath(),
        );
    }

    private function configuredHost(): string
    {
        $host = $this->laravel->make(Repository::class)->get('dump-to-console.host');

        return is_string($host) && $host !== '' ? $host : DumpClient::DEFAULT_HOST;
    }
}
