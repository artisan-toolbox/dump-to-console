<?php

declare(strict_types=1);

use ArtisanToolbox\DumpToConsole\DumpClient;
use ArtisanToolbox\DumpToConsole\DumpToConsole;
use ArtisanToolbox\DumpToConsole\Tests\Fixtures\BenchmarkCaller;
use ArtisanToolbox\DumpToConsole\Tests\Fixtures\DumpableValue;
use Illuminate\Foundation\DevCommands;
use Symfony\Component\VarDumper\Caster\ScalarStub;

beforeEach(function () {
    $this->client = new RecordingDumpClient;
    $this->app->instance(DumpClient::class, $this->client);
    $this->app->forgetInstance(DumpToConsole::class);
});

it('registers package services as singletons', function () {
    expect(app(DumpClient::class))->toBe(app(DumpClient::class))
        ->and(app(DumpToConsole::class))->toBe(app(DumpToConsole::class));
});

it('merges the package configuration', function () {
    expect(config('dump-to-console.host'))->toBe('tcp://127.0.0.1:9912')
        ->and(config('dump-to-console.register_dev_command'))->toBeTrue();
});

it('dumps and returns a single value through the global helper', function () {
    $line = __LINE__ + 1;
    $result = dc('value');

    expect($result)->toBe('value')
        ->and($this->client->dumps[0]['value'])->toBe('value')
        ->and($this->client->dumps[0]['context']['source']['file'])->toBe(__FILE__)
        ->and($this->client->dumps[0]['context']['source']['line'])->toBe($line)
        ->and($this->client->dumps[0]['label'])->toBeNull();
});

it('preserves labels and return values for multiple dumps', function () {
    $result = dc(first: 'one', second: 'two');

    expect($result)->toBe(['first' => 'one', 'second' => 'two'])
        ->and($this->client->dumps[0]['label'])->toBe('first')
        ->and($this->client->dumps[1]['label'])->toBe('second');
});

it('labels multiple positional dumps starting at one', function () {
    dc('one', 'two');

    expect($this->client->dumps[0]['label'])->toBe('1')
        ->and($this->client->dumps[1]['label'])->toBe('2');
});

it('sends a marker when no values are provided', function () {
    expect(dc())->toBeNull()
        ->and($this->client->dumps[0]['value'])
        ->toBeInstanceOf(ScalarStub::class);
});

it('resolves compiled blade dumps to the original view', function () {
    $compiledViewPath = dirname(__DIR__).DIRECTORY_SEPARATOR.'Fixtures';
    $alternateSeparator = DIRECTORY_SEPARATOR === '/' ? '\\' : '/';

    config()->set('view.compiled', str_replace(DIRECTORY_SEPARATOR, $alternateSeparator, $compiledViewPath));
    $this->app->forgetInstance(DumpToConsole::class);

    $result = require dirname(__DIR__).'/Fixtures/compiled-view.php';

    expect($result)->toBe('blade')
        ->and($this->client->dumps[0]['context']['source']['file'])
        ->toBe('/app/resources/views/example.blade.php')
        ->and($this->client->dumps[0]['context']['source']['line'])->toBeNull();
});

it('keeps fluent dumpable objects unchanged', function () {
    $value = new DumpableValue('Laravel');

    $line = __LINE__ + 1;
    $result = $value->dc('extra');

    expect($result)->toBe($value)
        ->and($this->client->dumps[0]['value'])->toBe($value)
        ->and($this->client->dumps[1]['value'])->toBe('extra')
        ->and($this->client->dumps[0]['context']['source']['line'])->toBe($line);
});

it('benchmarks once, dumps the duration and result, and returns the result', function () {
    $expected = new stdClass;
    $calls = 0;

    $result = BenchmarkCaller::run(function () use ($expected, &$calls) {
        $calls++;

        return $expected;
    });

    expect($result)->toBe($expected)
        ->and($calls)->toBe(1)
        ->and($this->client->dumps[0]['value'])->toEndWith('ms')
        ->and($this->client->dumps[0]['label'])->toBe('duration')
        ->and($this->client->dumps[1]['value'])->toBe($expected)
        ->and($this->client->dumps[1]['label'])->toBe('result')
        ->and($this->client->dumps[0]['context']['source']['file'])->toBe(BenchmarkCaller::file())
        ->and($this->client->dumps[0]['context']['source']['line'])->toBe(BenchmarkCaller::dumpLine());
});

it('never allows dump failures to affect the application', function () {
    $this->app->instance(DumpClient::class, new FailingDumpClient);
    $this->app->forgetInstance(DumpToConsole::class);

    expect(dc('value'))->toBe('value');
});

it('registers the listener command', function () {
    expect(Artisan::all())->toHaveKey('dump:listen');
});

it('registers the listener with the dev command when supported', function () {
    if (! class_exists(DevCommands::class)) {
        $this->markTestSkipped('This Laravel version does not expose DevCommands.');
    }

    expect(collect(DevCommands::commands())->firstWhere('name', 'dumps')['command'])
        ->toBe('php artisan dump:listen');
});

class RecordingDumpClient extends DumpClient
{
    /** @var list<array{value: mixed, context: array<string, mixed>, label: string|null}> */
    public array $dumps = [];

    public function __construct() {}

    public function dump(mixed $value, array $context = [], ?string $label = null): void
    {
        $this->dumps[] = compact('value', 'context', 'label');
    }
}

class FailingDumpClient extends DumpClient
{
    public function __construct() {}

    public function dump(mixed $value, array $context = [], ?string $label = null): void
    {
        throw new RuntimeException('Unable to dump the value.');
    }
}
