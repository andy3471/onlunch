<?php

declare(strict_types=1);

namespace App\Actions\UserSettings;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;

class UpdateUserSettingsAction
{
    use AsAction;

    public function execute(Site $site, User $user, string $workingHourPresetId): void
    {
        $user->sites()->updateExistingPivot($site->id, [
            'working_hour_preset_id' => $workingHourPresetId,
        ]);
    }
}
