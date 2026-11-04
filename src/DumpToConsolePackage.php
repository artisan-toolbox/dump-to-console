<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole;

use ArtisanToolbox\Maintainer\Versionable\Contracts\Versionable;
use ArtisanToolbox\Maintainer\Versionable\Contracts\WithReadmeHead;
use ArtisanToolbox\Maintainer\Versionable\Readme\Badge;
use ArtisanToolbox\Maintainer\Versionable\Readme\Head;

/** @internal Provides release metadata without booting the Laravel service. */
class DumpToConsolePackage implements Versionable, WithReadmeHead
{
    public const string VERSION = '1.0.0';

    public function readmeHead(): Head
    {
        return (new Head)
            ->withLogo(src: 'logo/dump-to-console-icon.svg', alt: '', width: 88)
            ->withTitle('Dump to Console')
            ->withDescription('Send Laravel dumps to a local console listener without interrupting the application.')
            ->addBadge(Badge::releaseVersion('https://github.com/artisan-toolbox/dump-to-console/releases'))
            ->addBadge(Badge::make(
                link: 'https://packagist.org/packages/artisan-toolbox/dump-to-console',
                imgSrc: 'https://img.shields.io/packagist/v/artisan-toolbox/dump-to-console.svg?style=flat-square',
                imgAlt: 'Packagist',
            ))
            ->addBadge(Badge::make(
                link: 'https://github.com/artisan-toolbox/dump-to-console/actions',
                imgSrc: 'https://img.shields.io/github/actions/workflow/status/artisan-toolbox/dump-to-console/tests.yml?branch=1.x&label=Tests&style=flat-square',
                imgAlt: 'Tests',
            ));
    }
}
