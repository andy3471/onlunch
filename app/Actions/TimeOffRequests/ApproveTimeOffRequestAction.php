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
                mb_substr((string) $block->getRawOriginal('start_time'), 0, 5),
                mb_substr((string) $block->getRawOriginal('end_time'), 0, 5),
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
