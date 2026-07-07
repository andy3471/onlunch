<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Site;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /** @var string */
    protected $rootView = 'app';

    /** Determine the current asset version. */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /** @return array<string, mixed> */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? fn (): array => [
                    'id'                    => $user->id,
                    'name'                  => $user->name,
                    'email'                 => $user->email,
                    'is_admin'              => ($site = $this->currentSite()) instanceof Site
                        ? $user->isSiteAdminFor($site)
                        : false,
                    'can_manage_time_off'     => ($site = $this->currentSite()) instanceof Site
                        ? $user->canAccessTimeOffManagement($site)
                        : false,
                ] : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error'   => fn () => $request->session()->get('error'),
            ],
            'config'      => fn (): array => $this->tenantConfig(),
            'currentSite' => fn (): ?array => $this->sharedCurrentSite(),
            'userSites'   => fn (): array => $this->userSites($request, $user),
        ];
    }

    private function currentSite(): ?Site
    {
        return app()->bound('currentSite') ? resolve('currentSite') : null;
    }

    /** @return array{id: string, name: string, slug: string}|null */
    private function sharedCurrentSite(): ?array
    {
        $site = $this->currentSite();

        if (! $site instanceof Site) {
            return null;
        }

        return [
            'id'   => $site->id,
            'name' => $site->name,
            'slug' => $site->slug,
        ];
    }

    /** @return array{appName: string, registerEnabled: bool, resetPasswordEnabled: bool, tasksEnabled: bool} */
    private function tenantConfig(): array
    {
        $site = $this->currentSite();

        return [
            'appName'              => config('app.name', 'Rota'),
            'registerEnabled'      => $site?->register_enabled                                ?? false,
            'resetPasswordEnabled' => $site?->reset_password_enabled                          ?? false,
            'tasksEnabled'         => $site?->teams()->where('tasks_enabled', true)->exists() ?? false,
        ];
    }

    /** @return list<array{id: string, name: string, slug: string, url: string, isCurrent: bool}> */
    private function userSites(Request $request, ?\App\Models\User $user): array
    {
        if ($user === null) {
            return [];
        }

        $currentSite = $this->currentSite();
        $domain      = (string) config('app.domain');
        $scheme      = $request->getScheme();

        return $user->sites()
            ->orderBy('name')
            ->get(['sites.id', 'sites.name', 'sites.slug'])
            ->map(fn (Site $site): array => [
                'id'        => $site->id,
                'name'      => $site->name,
                'slug'      => $site->slug,
                'url'       => "{$scheme}://{$site->slug}.{$domain}",
                'isCurrent' => $currentSite instanceof Site && $site->id === $currentSite->id,
            ])
            ->values()
            ->all();
    }
}
