<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Site|null $site */
        $site = app()->bound('currentSite') ? resolve('currentSite') : null;

        abort_unless($site && $request->user()?->isSiteAdminFor($site), 403);

        return $next($request);
    }
}
