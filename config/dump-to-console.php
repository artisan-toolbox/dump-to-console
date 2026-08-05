<?php

declare(strict_types=1);

use Illuminate\Support\Env;

return [

    /*
    |--------------------------------------------------------------------------
    | Dump Listener Host
    |--------------------------------------------------------------------------
    |
    | Dumps are sent to this local TCP endpoint. Keep the listener bound to a
    | trusted interface because the protocol does not provide authentication.
    |
    */
    'host' => Env::get('DUMP_TO_CONSOLE_HOST', 'tcp://127.0.0.1:9912'),

    /*
    |--------------------------------------------------------------------------
    | Laravel Development Command
    |--------------------------------------------------------------------------
    |
    | Supported Laravel versions can start the listener alongside the other
    | processes managed by "php artisan dev". The standalone command remains
    | available when this integration is disabled.
    |
    */
    'register_dev_command' => true,

];
