<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\DataTransferObjects\StoreTeamMemberData;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StoreTeamMemberAction
{
    use AsAction;

    public function __construct(
        private readonly SyncUserTeamMembershipsAction $syncTeamMemberships,
        private readonly SyncSiteMemberSettingsAction $syncSiteMemberSettings,
    ) {}

    public function execute(Site $site, StoreTeamMemberData|array $data): User
    {
        $payload = $data instanceof StoreTeamMemberData
            ? $data->toActionArray()
            : $data;

        $existing = User::query()->where('email', $payload['email'])->first();

        if ($existing instanceof User) {
            if ($existing->siteMembershipFor($site) !== null) {
                throw ValidationException::withMessages([
                    'email' => __('This user is already a member of this site.'),
                ]);
            }

            $user = $existing;
            $site->members()->attach($user->id, ['is_site_admin' => $payload['is_admin']]);
        } else {
            $user = User::query()->create([
                'name'     => $payload['name'],
                'email'    => $payload['email'],
                'password' => Hash::make((string) $payload['password']),
            ]);

            $site->members()->attach($user->id, ['is_site_admin' => $payload['is_admin']]);
        }

        $this->syncSiteMemberSettings->execute($site, $user, $payload);
        $this->syncTeamMemberships->execute($site, $user, $payload['team_memberships']);

        return $user->refresh();
    }
}
