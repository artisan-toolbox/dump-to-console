<?php

declare(strict_types=1);

it('installs the Maintainer contracts for consumers', function () {
    $manifest = json_decode(file_get_contents(dirname(__DIR__, 2).'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

    expect($manifest['require'])->toHaveKey('artisan-toolbox/maintainer');
});
