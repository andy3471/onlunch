<?php

declare(strict_types=1);

arch('actions expose a single execute method')
    ->expect('App\Actions')
    ->toHaveMethod('execute')
    ->ignoring('App\Actions\Concerns\AsAction');

arch('env helper is not used in the application layer')
    ->expect('App')
    ->not->toUse('env')
    ->ignoring([
        'App\Providers',
    ]);
