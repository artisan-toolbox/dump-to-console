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
        ->and(array_map(static fn (Badge $badge): string => $badge->link(), $head->badges()))->toBe([
            'https://github.com/artisan-toolbox/dump-to-console/releases',
            'https://packagist.org/packages/artisan-toolbox/dump-to-console',
            'https://github.com/artisan-toolbox/dump-to-console/actions',
        ]);
    expect(DumpToConsole::VERSION)->toBe(DumpToConsolePackage::VERSION);
});
