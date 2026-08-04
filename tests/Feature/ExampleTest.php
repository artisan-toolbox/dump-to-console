<?php

declare(strict_types=1);

use ArtisanToolbox\DumpToConsole\DumpToConsole;

it('resolves the singleton', function () {
    expect(app(DumpToConsole::class))->toBeInstanceOf(DumpToConsole::class);
});

it('returns the same instance from the container', function () {
    expect(app(DumpToConsole::class))->toBe(app(DumpToConsole::class));
});

it('merges the package config', function () {
    expect(config('dump-to-console.placeholder'))->toBe('default');
});

it('loads the package translations', function () {
    expect(trans('dump-to-console::messages.placeholder'))->toBe('DumpToConsole placeholder translation.');
});

it('loads the package views', function () {
    expect(view()->exists('dump-to-console::placeholder'))->toBeTrue();
});

it('registers the artisan command', function () {
    $this->artisan('dump-to-console:placeholder')
        ->expectsOutputToContain('DumpToConsole placeholder command executed.')
        ->assertSuccessful();
});
