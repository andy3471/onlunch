<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Facade;

return [

    'domain' => env('APP_DOMAIN', 'localhost'),

    /*
    | Comma-separated proxy IPs/CIDRs, or "*" for local Sail / trusted edge.
    | Prefer explicit CIDRs in production.
    */
    'trusted_proxies' => env('TRUSTED_PROXIES', '*'),

    'aliases' => Facade::defaultAliases()->merge([
        'Redis' => Illuminate\Support\Facades\Redis::class,
    ])->toArray(),

];
