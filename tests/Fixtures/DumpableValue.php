<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole\Tests\Fixtures;

use ArtisanToolbox\DumpToConsole\Concerns\Dumpable;

class DumpableValue
{
    use Dumpable;

    public function __construct(public string $value) {}
}
