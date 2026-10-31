<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Site;
use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'admin:make {user : User email} {site : Site slug}';

    protected $description = 'Grant site admin on the given site slug';

    public function handle(): int
    {
        $user = User::query()->where('email', $this->argument('user'))->first();

        if (! $user instanceof User) {
            $this->warn('User not found');

            return self::FAILURE;
        }

        $site = Site::query()->where('slug', $this->argument('site'))->first();

        if (! $site instanceof Site) {
            $this->warn('Site not found');

            return self::FAILURE;
        }

        if ($user->siteMembershipFor($site) === null) {
            $site->members()->attach($user->id, [
                'is_site_admin'            => true,
                'onboarding_completed_at'  => now(),
            ]);
        } else {
            $site->members()->updateExistingPivot($user->id, [
                'is_site_admin' => true,
            ]);
        }

        $this->info("{$user->name} is now a site admin for {$site->slug}");

        return self::SUCCESS;
    }
}
