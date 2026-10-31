<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserBelongsToSite
{
    public function handle(Request $request, Closure $next): Response
    {
        $site = app()->bound(Site::class) ? app(Site::class) : null;
        $user = $request->user();

        if (! $site instanceof Site || $user === null) {
            return $next($request);
        }

        if ($user->siteMembershipFor($site) !== null) {
            return $next($request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->guest('/login')
            ->withErrors(['email' => __('You do not have access to this site.')]);
    }
}
