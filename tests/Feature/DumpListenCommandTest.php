<?php

declare(strict_types=1);

use ArtisanToolbox\DumpToConsole\Console\Commands\DumpListenCommand;
use ArtisanToolbox\DumpToConsole\DumpServer;
use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\VarDumper\Cloner\VarCloner;

it('listens for and renders incoming dumps', function () {
    $server = new FakeDumpServer;
    $command = new TestDumpListenCommand($server);

    app(Kernel::class)->registerCommand($command);

    expect(app(Kernel::class)->call('dump:listen'))->toBe(0)
        ->and(app(Kernel::class)->output())
        ->toContain(
            'Listening for dumps on [tcp://127.0.0.1:9912].',
            '"Taylor"',
            'routes/web.php:10',
        )
        ->and($server->calls)->toBe(['start', 'listen']);
});

class TestDumpListenCommand extends DumpListenCommand
{
    public function __construct(private readonly DumpServer $dumpServer)
    {
        parent::__construct();
    }

    protected function server(): DumpServer
    {
        return $this->dumpServer;
    }
}

class FakeDumpServer extends DumpServer
{
    /** @var list<string> */
    public array $calls = [];

    public function __construct() {}

    public function start(): void
    {
        $this->calls[] = 'start';
    }

    public function listen(callable $callback): void
    {
        $this->calls[] = 'listen';

        $callback(
            (new VarCloner)->cloneVar(['name' => 'Taylor']),
            [
                'source' => [
                    'file' => base_path('routes/web.php'),
                    'file_relative' => 'routes/web.php',
                    'line' => 10,
                ],
            ],
            1,
        );
    }

    public function host(): string
    {
        return 'tcp://127.0.0.1:9912';
    }
}
