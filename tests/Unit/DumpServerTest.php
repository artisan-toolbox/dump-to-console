<?php

declare(strict_types=1);

use ArtisanToolbox\DumpToConsole\DumpClient;
use ArtisanToolbox\DumpToConsole\DumpServer;

it('normalizes the server host', function () {
    expect((new DumpServer('127.0.0.1:9912'))->host())->toBe('tcp://127.0.0.1:9912');
});

it('receives dumps from the client', function () {
    if (! function_exists('pcntl_fork')) {
        $this->markTestSkipped('The PCNTL extension is required.');
    }

    $socket = stream_socket_server('tcp://127.0.0.1:0');
    $host = stream_socket_get_name($socket, false);
    fclose($socket);

    $server = new DumpServer($host);
    $server->start();
    $processId = pcntl_fork();

    if ($processId === -1) {
        $this->fail('Unable to fork the dump client process.');
    }

    if ($processId === 0) {
        (new DumpClient($host))->dump('value', ['test' => true]);

        exit(0);
    }

    $received = null;

    try {
        $server->listen(function ($data, $context) use (&$received): void {
            $received = [$data->getValue(true), $context];

            throw new StopListening;
        });
    } catch (StopListening) {
        // The expected dump was received, so the blocking listener can stop.
    } finally {
        pcntl_waitpid($processId, $status);
    }

    expect($received[0])->toBe('value')
        ->and($received[1]['test'])->toBeTrue()
        ->and($received[1]['timestamp'])->toBeFloat();
});

class StopListening extends RuntimeException {}
