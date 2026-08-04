<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole\Console\Commands;

use Illuminate\Console\Command;

class DumpToConsoleCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'dump-to-console:placeholder';

    /**
     * The command description.
     */
    protected $description = 'Placeholder Artisan command shipped by the package dump-to-console.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('DumpToConsole placeholder command executed.');

        return self::SUCCESS;
    }
}
