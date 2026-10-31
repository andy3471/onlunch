<?php

declare(strict_types=1);

namespace App\Actions\Sites;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterSiteAction
{
    use AsAction;

    /**
     * @param  array{team_name: string, team_slug: string, name: string, email: string, password: string}  $data
     * @return array{site: Site, user: User}
     */
    public function execute(array $data): array
    {
        return DB::transaction(function () use ($data): array {
            $site = Site::query()->create([
                'name'                   => $data['team_name'],
                'slug'                   => $data['team_slug'],
                'register_enabled'       => true,
                'reset_password_enabled' => false,
            ]);

            $team = $site->teams()->create([
                'name' => 'Everyone',
            ]);

            $user = User::query()->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $site->members()->attach($user->id, ['is_site_admin' => true]);
            $team->members()->attach($user->id, ['is_scheduled' => true]);

            return ['site' => $site, 'user' => $user];
        });
    }
}
