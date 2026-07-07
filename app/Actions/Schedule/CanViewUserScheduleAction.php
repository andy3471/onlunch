<?php

declare(strict_types=1);

namespace App\Actions\Schedule;

use App\Actions\Concerns\AsAction;
use App\Enums\ScheduleVisibility;
use App\Models\Site;
use App\Models\Team;
use App\Models\User;

class CanViewUserScheduleAction
{
    use AsAction;

    public function execute(Site $site, ?User $viewer, User $subject): bool
    {
        if ($viewer === null) {
            return false;
        }

        if ($viewer->id === $subject->id) {
            return true;
        }

        if ($viewer->isSiteAdminFor($site)) {
            return true;
        }

        $subjectTeams = $subject->teams()
            ->where('site_id', $site->id)
            ->get();

        foreach ($subjectTeams as $team) {
            if ($this->membershipGrantsVisibility($site, $viewer, $subject, $team)) {
                return true;
            }
        }

        return false;
    }

    protected function membershipGrantsVisibility(
        Site $site,
        User $viewer,
        User $subject,
        Team $team,
    ): bool {
        $membership = $subject->membershipFor($team);

        if ($membership === null) {
            return false;
        }

        $visibility = $membership->schedule_visibility instanceof ScheduleVisibility
            ? $membership->schedule_visibility
            : ScheduleVisibility::tryFrom((string) $membership->schedule_visibility)
                ?? ScheduleVisibility::Everyone;

        return match ($visibility) {
            ScheduleVisibility::Everyone  => $viewer->sites()->whereKey($site->id)->exists(),
            ScheduleVisibility::Team      => $viewer->teams()->whereKey($team->id)->exists(),
            ScheduleVisibility::Approvers => $viewer->canApproveTimeOffFor($team),
            ScheduleVisibility::Private   => false,
        };
    }
}
