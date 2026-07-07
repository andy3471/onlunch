<?php

declare(strict_types=1);

namespace App\Actions\TimeOffRequests;

use App\Actions\Availability\AssertMinimumAvailabilityAction;
use App\Actions\Concerns\AsAction;
use App\Actions\Schedule\AssertNoScheduleOverlapAction;
use App\Enums\TimeOffRequestStatus;
use App\Models\Site;
use App\Models\Team;
use App\Models\TimeOffRequest;
use App\Models\User;

class RequestTimeOffAction
{
    use AsAction;

    public function __construct(
        private readonly AssertMinimumAvailabilityAction $assertMinimumAvailability,
        private readonly AssertNoScheduleOverlapAction $assertNoScheduleOverlap,
    ) {}

    public function execute(
        Site $site,
        Team $team,
        User $user,
        string $date,
        string $startTime,
        string $endTime,
        ?string $notes = null,
    ): TimeOffRequest {
        $this->assertMinimumAvailability->execute(
            $team,
            $date,
            $startTime,
            $endTime,
            $user->id,
        );

        $this->assertNoScheduleOverlap->execute($user, $date, $startTime, $endTime);

        $status = $user->requiresTimeOffApproval($site)
            ? TimeOffRequestStatus::Pending
            : TimeOffRequestStatus::Approved;

        return TimeOffRequest::createWithTimeBlock(
            [
                'team_id' => $team->id,
                'user_id' => $user->id,
                'status'  => $status->value,
                'notes'   => $notes,
            ],
            [
                'date'       => $date,
                'start_time' => $startTime,
                'end_time'   => $endTime,
            ],
        );
    }
}
