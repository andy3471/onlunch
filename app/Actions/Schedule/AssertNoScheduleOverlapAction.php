<?php

declare(strict_types=1);

namespace App\Actions\Schedule;

use App\Actions\Concerns\AsAction;
use App\Models\TimeBlock;
use App\Models\User;
use App\Support\TimeInput;
use Illuminate\Validation\ValidationException;

class AssertNoScheduleOverlapAction
{
    use AsAction;

    public function execute(
        User $user,
        string $date,
        string $startTime,
        string $endTime,
        ?string $ignoreTimeBlockId = null,
    ): void {
        $start = TimeInput::forStorage($startTime);
        $end   = TimeInput::forStorage($endTime);

        $overlaps = TimeBlock::query()
            ->where('user_id', $user->id)
            ->where('date', $date)
            ->when($ignoreTimeBlockId !== null, fn ($query) => $query->where('id', '!=', $ignoreTimeBlockId))
            ->countsForSchedule()
            ->overlapping($start, $end)
            ->exists();

        if ($overlaps) {
            throw ValidationException::withMessages([
                'start_time' => 'This time overlaps with something else on the schedule.',
            ]);
        }
    }
}
