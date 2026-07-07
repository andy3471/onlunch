<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $site = app()->bound('currentSite') ? resolve('currentSite') : null;

        abort_if(! $site || ! $site->{$feature}, 404);

        return $next($request);
    }
}
