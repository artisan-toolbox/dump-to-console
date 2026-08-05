<?php

declare(strict_types=1);

use ArtisanToolbox\DumpToConsole\DumpClient;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\Stub;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

it('sends cloned values, labels, and context using the dump server protocol', function () {
    $server = stream_socket_server('tcp://127.0.0.1:0');
    $host = stream_socket_get_name($server, false);

    $client = new DumpClient($host);
    $client->dump(['name' => 'Taylor'], [
        'source' => ['file' => '/app/routes/web.php', 'line' => 10],
    ], 'result');

    $connection = stream_socket_accept($server, 1);
    [$data, $context] = unserialize(base64_decode((string) fgets($connection)), [
        'allowed_classes' => [Data::class, Stub::class],
    ]);

    expect($data)->toBeInstanceOf(Data::class)
        ->and($data->getValue(true))->toBe(['name' => 'Taylor'])
        ->and($data->getContext()['label'])->toBe('result')
        ->and($context['timestamp'])->toBeFloat()
        ->and($context['source'])->toBe(['file' => '/app/routes/web.php', 'line' => 10]);

    fclose($connection);
    fclose($server);
});

it('normalizes specialized stubs for server compatibility', function () {
    $server = stream_socket_server('tcp://127.0.0.1:0');
    $host = stream_socket_get_name($server, false);

    $client = new DumpClient($host);
    $client->dump(DumpClientFixture::class);

    $connection = stream_socket_accept($server, 1);
    [$data] = unserialize(base64_decode((string) fgets($connection)), [
        'allowed_classes' => [Data::class, Stub::class],
    ]);

    $dumper = new CliDumper;

    expect($dumper->dump($data, true))->toBe(
        $dumper->dump((new VarCloner)->cloneVar(DumpClientFixture::class), true),
    );

    fclose($connection);
    fclose($server);
});

it('silently discards dumps when the server is unavailable', function () {
    (new DumpClient('tcp://127.0.0.1:1'))->dump('value');

    expect(true)->toBeTrue();
});

it('uses a non-blocking socket', function () {
    $server = stream_socket_server('tcp://127.0.0.1:0');
    $host = stream_socket_get_name($server, false);
    $client = new TestableDumpClient($host);

    expect($client->connectForTesting())->toBeTrue()
        ->and(stream_get_meta_data($client->socketForTesting())['blocked'])->toBeFalse();

    fclose($server);
});

class TestableDumpClient extends DumpClient
{
    public function connectForTesting(): bool
    {
        return $this->connect();
    }

    /** @return resource|null */
    public function socketForTesting()
    {
        return $this->socket;
    }
}

class DumpClientFixture
{
    public static string $value = 'Laravel';
}
