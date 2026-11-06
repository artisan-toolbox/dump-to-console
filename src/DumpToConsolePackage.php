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
    public const string VERSION = '1.1.0';

    public function readmeHead(): Head
    {
        return (new Head)
            ->withLogo(src: 'logo/dump-to-console-icon.svg', alt: '', width: 88)
            ->withTitle('Dump to Console')
            ->withDescription('Send Laravel dumps to a local console listener without interrupting the application.')
            ->addBadge(Badge::make(
                link: 'https://github.com/artisan-toolbox/dump-to-console/releases',
                imgSrc: 'https://img.shields.io/github/v/release/artisan-toolbox/dump-to-console?label=Release&style=flat-square',
                imgAlt: 'Release',
            ))
            ->addBadge(Badge::make(
                link: 'https://atb.dev.br/packages/dump-to-console/#requirements',
                imgSrc: 'https://img.shields.io/badge/PHP-%5E8.5-777BB4?style=flat-square',
                imgAlt: 'PHP',
            ))
            ->addBadge(Badge::make(
                link: 'https://atb.dev.br/packages/dump-to-console/#requirements',
                imgSrc: 'https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square',
                imgAlt: 'Laravel',
            ))
            ->addBadge(Badge::make(
                link: 'https://github.com/artisan-toolbox/dump-to-console/actions/workflows/tests.yml?query=branch%3A1.x',
                imgSrc: 'https://img.shields.io/github/actions/workflow/status/artisan-toolbox/dump-to-console/tests.yml?branch=1.x&label=Tests&style=flat-square',
                imgAlt: 'Tests',
            ))
            ->addBadge(Badge::make(
                link: 'https://atb.dev.br/packages/dump-to-console/',
                imgSrc: 'https://img.shields.io/badge/Docs-Read-blue?style=flat-square',
                imgAlt: 'Docs',
            ));
    }
}
