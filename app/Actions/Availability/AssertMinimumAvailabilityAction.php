<?php

declare(strict_types=1);

namespace App\Actions\Availability;

use App\Actions\Concerns\AsAction;
use App\Models\Team;
use Illuminate\Validation\ValidationException;

class AssertMinimumAvailabilityAction
{
    use AsAction;

    public function __construct(
        private readonly CountAvailableStaffAction $countAvailableStaff,
    ) {}

    public function execute(
        Team $team,
        string $date,
        string $startTime,
        string $endTime,
        ?string $excludeUserId = null,
    ): void {
        if (! $team->hasMinimumAvailabilityRule()) {
            return;
        }

        $available = $this->countAvailableStaff->execute(
            $team,
            $date,
            $startTime,
            $endTime,
            $excludeUserId,
        );

        if ($available < $team->minimum_available_staff) {
            throw ValidationException::withMessages([
                'start_time' => sprintf(
                    'At least %d staff must remain available during this time. Only %d would be available.',
                    $team->minimum_available_staff,
                    $available,
                ),
            ]);
        }
    }
}
