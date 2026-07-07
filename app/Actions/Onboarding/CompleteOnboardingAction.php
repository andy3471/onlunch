<?php

declare(strict_types=1);

namespace App\Actions\Onboarding;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class CompleteOnboardingAction
{
    use AsAction;

    public function execute(Site $site, User $user, string $workingHourPresetId): void
    {
        $user->sites()->updateExistingPivot($site->id, [
            'working_hour_preset_id'  => $workingHourPresetId,
            'onboarding_completed_at' => Date::now(),
        ]);
    }
}
