<?php

declare(strict_types=1);

namespace App\Actions\Availability;

use App\Actions\Concerns\AsAction;
use App\Enums\TimeOffRequestStatus;
use App\Models\LunchBooking;
use App\Models\TaskAssignment;
use App\Models\Team;
use App\Models\TimeBlock;
use App\Models\TimeOffRequest;
use App\Models\User;
use Illuminate\Support\Collection;

class CountAvailableStaffAction
{
    use AsAction;

    public function execute(
        Team $team,
        string $date,
        string $startTime,
        string $endTime,
        ?string $excludeUserId = null,
    ): int {
        $scheduledMembers = $team->members()
            ->wherePivot('is_scheduled', true)
            ->when($excludeUserId, fn ($query) => $query->where('users.id', '!=', $excludeUserId))
            ->get();

        $unavailableUserIds = $this->unavailableUserIds($team, $date, $startTime, $endTime, $excludeUserId);

        return $scheduledMembers->reject(
            fn (User $user) => $unavailableUserIds->contains($user->id),
        )->count();
    }

    /** @return Collection<int, string> */
    private function unavailableUserIds(
        Team $team,
        string $date,
        string $startTime,
        string $endTime,
        ?string $excludeUserId,
    ): Collection {
        return TimeBlock::query()
            ->where('team_id', $team->id)
            ->where('date', $date)
            ->when($excludeUserId, fn ($query) => $query->where('user_id', '!=', $excludeUserId))
            ->where(function ($query) use ($startTime, $endTime): void {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->with('blockable')
            ->get()
            ->filter(fn (TimeBlock $block): bool => $this->blockReducesAvailability($block))
            ->pluck('user_id')
            ->unique()
            ->values();
    }

    private function blockReducesAvailability(TimeBlock $block): bool
    {
        $blockable = $block->blockable;

        if ($blockable instanceof LunchBooking) {
            return true;
        }

        if ($blockable instanceof TimeOffRequest) {
            $status = $blockable->status instanceof TimeOffRequestStatus
                ? $blockable->status
                : TimeOffRequestStatus::from((string) $blockable->status);

            return in_array($status, [TimeOffRequestStatus::Approved, TimeOffRequestStatus::Pending], true);
        }

        if ($blockable instanceof TaskAssignment) {
            return $blockable->isAway();
        }

        return false;
    }
}
