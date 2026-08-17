<?php

declare(strict_types=1);

use ArtisanToolbox\DumpToConsole\DumpToConsole;

if (! function_exists('dc')) {
    /**
     * Send values to the dump listener without changing application output.
     *
     * @template TValue
     *
     * @param  TValue  ...$values
     * @return ($values is empty ? null : (TValue|array<array-key, TValue>))
     */
    function dc(mixed ...$values): mixed
    {
        return resolve(DumpToConsole::class)->dump(...$values);
    }
}
