<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanManageTimeOff
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Site|null $site */
        $site = app()->bound('currentSite') ? resolve('currentSite') : null;
        $user = $request->user();

        abort_unless($site && $user?->canAccessTimeOffManagement($site), 403);

        return $next($request);
    }
}
