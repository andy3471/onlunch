<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /** @param  Closure(Request): Response  $next */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        /** @var Site|null $site */
        $site = app()->bound('currentSite') ? resolve('currentSite') : null;

        if (! $site) {
            return $next($request);
        }

        if ($user->hasCompletedOnboardingFor($site)) {
            return $next($request);
        }

        if ($request->routeIs('onboarding.*')) {
            return $next($request);
        }

        return redirect('/onboarding');
    }
}
