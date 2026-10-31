<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Site;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        $this->configureModels();
        $this->configureCarbon();
        $this->configureDatabase();
        $this->configureVite();
        $this->configureAuth();
        $this->configurePasswordDefaults();
        $this->configureRateLimiting();
    }

    private function configureAuth(): void
    {
        Gate::define('admin', function (User $user): bool {
            $site = app()->bound(Site::class) ? app(Site::class) : null;

            return $site instanceof Site && $user->isSiteAdminFor($site);
        });
    }

    private function configurePasswordDefaults(): void
    {
        Password::defaults(static function (): Password {
            $rule = Password::min(8);

            if (app()->environment('production')) {
                return $rule->mixedCase()->numbers()->symbols()->uncompromised();
            }

            return $rule;
        });
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', static function (Request $request): Limit {
            return Limit::perMinute(5)->by(mb_strtolower((string) $request->input('email')).'|'.$request->ip());
        });
    }

    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }

    private function configureDatabase(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
        );
    }

    private function configureCarbon(): void
    {
        Date::use(CarbonImmutable::class);
        CarbonInterval::enableFloatSetters();
    }

    private function configureModels(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
    }
}
