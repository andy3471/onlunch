<?php

declare(strict_types=1);

use App\Http\Middleware\ResolveTenantFromSubdomain;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            $domain = config('app.domain');

            // Main domain: brochure / marketing pages
            Route::middleware('web')
                ->domain($domain)
                ->group(base_path('routes/web.php'));

            // Tenant subdomains: app routes (Inertia frontend)
            Route::middleware(['web', ResolveTenantFromSubdomain::class])
                ->domain('{tenant}.'.$domain)
                ->group(base_path('routes/tenant.php'));

            // API routes
            Route::prefix('api')
                ->middleware('web')
                ->name('api.')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->redirectGuestsTo(function (): string {
            if (request()->route('tenant') !== null) {
                return '/login';
            }

            $domain = config('app.domain');
            $host   = request()->getHost();

            if ($host !== $domain && str_ends_with($host, '.'.$domain)) {
                return '/login';
            }

            return route('brochure.home');
        });
        $middleware->redirectUsersTo('/');

        $middleware->web(append: [
            App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->append(App\Http\Middleware\CheckForMaintenanceMode::class);

        $middleware->throttleApi('60,1');

        $middleware->alias([
            'bindings'            => Illuminate\Routing\Middleware\SubstituteBindings::class,
            'team.feature'        => App\Http\Middleware\EnsureTeamFeatureEnabled::class,
            'onboarding.complete' => App\Http\Middleware\EnsureOnboardingComplete::class,
            'admin'               => App\Http\Middleware\EnsureUserIsAdmin::class,
            'time_off.manage'     => App\Http\Middleware\EnsureUserCanManageTimeOff::class,
        ]);

        $middleware->priority([
            Illuminate\Session\Middleware\StartSession::class,
            Illuminate\View\Middleware\ShareErrorsFromSession::class,
            Illuminate\Routing\Middleware\ThrottleRequests::class,
            Illuminate\Session\Middleware\AuthenticateSession::class,
            Illuminate\Routing\Middleware\SubstituteBindings::class,
            Illuminate\Auth\Middleware\Authorize::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        Integration::handles($exceptions);
    })->create();
