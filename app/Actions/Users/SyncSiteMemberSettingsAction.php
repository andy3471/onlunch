<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Enums\TimeOffApprovalMode;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class SyncSiteMemberSettingsAction
{
    use AsAction;

    /** @param  array<string, mixed>  $data */
    public function execute(Site $site, User $user, array $data): void
    {
        $mode = TimeOffApprovalMode::tryFrom((string) ($data['time_off_approval'] ?? 'site_default'))
            ?? TimeOffApprovalMode::SiteDefault;

        $pivot = [
            'is_site_admin'              => $data['is_admin'],
            'working_hour_preset_id'     => $data['working_hour_preset_id'] ?? null,
            'time_off_requires_approval' => $mode->toNullableBool(),
        ];

        if ($pivot['working_hour_preset_id'] !== null) {
            $pivot['onboarding_completed_at'] = Date::now();
        }

        $user->sites()->updateExistingPivot($site->id, $pivot);
    }
}
