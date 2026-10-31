<?php

declare(strict_types=1);

namespace App\Actions\TimeOffRequests;

use App\Actions\Availability\AssertMinimumAvailabilityAction;
use App\Actions\Concerns\AsAction;
use App\Enums\TimeOffRequestStatus;
use App\Models\TimeOffRequest;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class ApproveTimeOffRequestAction
{
    use AsAction;

    public function __construct(
        private readonly AssertMinimumAvailabilityAction $assertMinimumAvailability,
    ) {}

    public function execute(TimeOffRequest $request, User $reviewer): TimeOffRequest
    {
        $block = $request->timeBlock;

        if ($block !== null) {
            $this->assertMinimumAvailability->execute(
                $request->team,
                $block->date->toDateString(),
                $block->start_time,
                $block->end_time,
                $request->user_id,
            );
        }

        $request->update([
            'status'      => TimeOffRequestStatus::Approved,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => Date::now(),
        ]);

        return $request->refresh();
    }
}
