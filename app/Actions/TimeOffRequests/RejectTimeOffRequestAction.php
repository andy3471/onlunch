<?php

declare(strict_types=1);

namespace App\Actions\TimeOffRequests;

use App\Actions\Concerns\AsAction;
use App\Enums\TimeOffRequestStatus;
use App\Models\TimeOffRequest;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class RejectTimeOffRequestAction
{
    use AsAction;

    public function execute(TimeOffRequest $request, User $reviewer): TimeOffRequest
    {
        $request->update([
            'status'      => TimeOffRequestStatus::Rejected,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => Date::now(),
        ]);

        return $request->refresh();
    }
}
