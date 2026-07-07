<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class SyncSiteMemberSettingsAction
{
    use AsAction;

    public function execute(Site $site, User $user, array $data): void
    {
        $timeOffRequiresApproval = match ($data['time_off_approval'] ?? 'site_default') {
            'required' => true,
            'auto'     => false,
            default    => null,
        };

        $pivot = [
            'is_site_admin'              => $data['is_admin'],
            'working_hour_preset_id'     => $data['working_hour_preset_id'] ?? null,
            'time_off_requires_approval' => $timeOffRequiresApproval,
        ];

        if ($pivot['working_hour_preset_id'] !== null) {
            $pivot['onboarding_completed_at'] = Date::now();
        }

        $user->sites()->updateExistingPivot($site->id, $pivot);
    }
}
