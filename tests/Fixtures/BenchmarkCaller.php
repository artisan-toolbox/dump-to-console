<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole\Tests\Fixtures;

use Illuminate\Support\Benchmark;

class BenchmarkCaller
{
    public const DUMP_LINE = 15;

    public static function run(callable $callback): mixed
    {
        return Benchmark::dc($callback);
    }

    public static function file(): string
    {
        return __FILE__;
    }

    public static function dumpLine(): int
    {
        return self::DUMP_LINE;
    }
}
