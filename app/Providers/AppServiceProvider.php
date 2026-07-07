<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        $this->configureModels();
        $this->configureCarbon();
        $this->configureDatabase();
        $this->configureVite();
        $this->bootAuth();
    }

    public function bootAuth(): void
    {
        Gate::define('admin', function ($user) {
            $site = app()->bound('currentSite') ? resolve('currentSite') : null;

            return $site instanceof \App\Models\Site && $user->isSiteAdminFor($site);
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
