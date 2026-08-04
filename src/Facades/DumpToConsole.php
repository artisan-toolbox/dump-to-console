<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ArtisanToolbox\DumpToConsole\DumpToConsole
 */
class DumpToConsole extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ArtisanToolbox\DumpToConsole\DumpToConsole::class;
    }
}
