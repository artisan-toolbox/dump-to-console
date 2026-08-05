<?php

declare(strict_types=1);

use ArtisanToolbox\DumpToConsole\Console\CliDumper;
use ArtisanToolbox\DumpToConsole\DumpRenderer;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\VarDumper\Cloner\VarCloner;

it('renders dumps with their source', function () {
    $output = new BufferedOutput;
    $renderer = new DumpRenderer(new CliDumper($output, '/app', ''), '/app');

    $renderer->render(
        (new VarCloner)->cloneVar(['name' => 'Taylor']),
        ['source' => ['file' => '/app/routes/web.php', 'line' => 10]],
    );

    expect($output->fetch())->toContain('"Taylor"', 'routes/web.php:10');
});

it('ignores malformed source context', function (mixed $source) {
    $output = new BufferedOutput;
    $renderer = new DumpRenderer(new CliDumper($output, '/app', ''), '/app');

    $renderer->render(
        (new VarCloner)->cloneVar('value'),
        ['source' => $source],
    );

    expect($output->fetch())->toBe("\"value\"\n");
})->with([
    'non-array source' => 'invalid',
    'missing file' => [['line' => 10]],
    'non-string file' => [['file' => null, 'line' => 'invalid']],
]);
