<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole\Concerns;

trait Dumpable
{
    /**
     * Dump this object and any additional values to the console.
     *
     * @return $this
     */
    public function dc(mixed ...$values): static
    {
        dc($this, ...$values);

        return $this;
    }
}
