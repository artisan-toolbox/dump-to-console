<?php

declare(strict_types=1);

use ArtisanToolbox\DumpToConsole\DumpToConsole;
use ArtisanToolbox\DumpToConsole\DumpToConsolePackage;
use ArtisanToolbox\Maintainer\Versionable\Readme\Badge;

it('configures the package logo and badges for the managed README header', function () {
    $head = (new DumpToConsolePackage)->readmeHead();

    expect($head->title())->toBe('Dump to Console')
        ->and($head->logo())->toBe(['src' => 'logo/dump-to-console-icon.svg', 'alt' => '', 'width' => 88])
        ->and(dirname(__DIR__, 2).'/logo/dump-to-console-icon.svg')->toBeFile()
        ->and(array_map(static fn (Badge $badge): array => [
            $badge->imgAlt(), $badge->link(), $badge->imgSrc(),
        ], $head->badges()))->toBe([
            ['Release', 'https://github.com/artisan-toolbox/dump-to-console/releases', 'https://img.shields.io/github/v/release/artisan-toolbox/dump-to-console?label=Release&style=flat-square'],
            ['PHP', 'https://artisantoolbox.wsssoftware.com.br/packages/dump-to-console/#requirements', 'https://img.shields.io/badge/PHP-%5E8.5-777BB4?style=flat-square'],
            ['Laravel', 'https://artisantoolbox.wsssoftware.com.br/packages/dump-to-console/#requirements', 'https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square'],
            ['Tests', 'https://github.com/artisan-toolbox/dump-to-console/actions/workflows/tests.yml?query=branch%3A1.x', 'https://img.shields.io/github/actions/workflow/status/artisan-toolbox/dump-to-console/tests.yml?branch=1.x&label=Tests&style=flat-square'],
            ['Docs', 'https://artisantoolbox.wsssoftware.com.br/packages/dump-to-console/', 'https://img.shields.io/badge/Docs-Read-blue?style=flat-square'],
        ]);
    expect(DumpToConsole::VERSION)->toBe(DumpToConsolePackage::VERSION);
});
