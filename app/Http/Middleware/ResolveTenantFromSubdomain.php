<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantFromSubdomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host   = $request->getHost();
        $domain = config('app.domain');

        $subdomain = str_replace('.'.$domain, '', $host);

        abort_if(! $subdomain || $subdomain === $host, 404);

        $site = Site::where('slug', $subdomain)->first();

        abort_unless($site, 404);

        app()->instance('currentSite', $site);
        $request->merge(['tenant' => $site]);

        view()->share('currentSite', $site);

        return $next($request);
    }
}
