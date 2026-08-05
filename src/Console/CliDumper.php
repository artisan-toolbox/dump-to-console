<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole\Console;

use Illuminate\Foundation\Console\CliDumper as LaravelCliDumper;
use Symfony\Component\VarDumper\Cloner\Data;

class CliDumper extends LaravelCliDumper
{
    /** @var array{0: string, 1: string, 2: int|null}|null */
    private ?array $source = null;

    /** @param array{0: string, 1: string, 2: int|null}|null $source */
    public function dumpWithSource(Data $data, ?array $source = null): void
    {
        $this->source = $source;

        try {
            parent::dumpWithSource($data);
        } finally {
            $this->source = null;
        }
    }

    /** @return array{0: string, 1: string, 2: int|null}|null */
    public function resolveDumpSource(): ?array
    {
        return $this->source;
    }
}
