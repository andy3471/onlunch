<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Site;
use App\Models\TimeOffRequest;
use App\Models\User;

class TimeOffRequestPolicy
{
    public function create(User $user): bool
    {
        $site = app()->bound(Site::class) ? app(Site::class) : null;

        return $site instanceof Site && $user->siteMembershipFor($site) !== null;
    }

    public function approve(User $user, TimeOffRequest $timeOffRequest): bool
    {
        $timeOffRequest->loadMissing('team');

        return $user->canApproveTimeOffFor($timeOffRequest->team);
    }

    public function reject(User $user, TimeOffRequest $timeOffRequest): bool
    {
        return $this->approve($user, $timeOffRequest);
    }
}
