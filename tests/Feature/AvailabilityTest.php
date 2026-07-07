<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Availability\AssertMinimumAvailabilityAction;
use App\Models\LunchBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_lunch_booking_blocked_when_minimum_availability_would_be_violated(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme'], [
            'minimum_available_staff' => 2,
        ]);

        $this->attachOnboardedMember($site, $team, User::factory()->create(), [
            'is_scheduled' => true,
        ]);

        $secondUser = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $secondUser, [
            'is_scheduled' => true,
        ]);

        $firstUser = $team->members()->first();

        LunchBooking::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $firstUser->id],
            ['date' => now()->toDateString(), 'start_time' => '12:00', 'end_time' => '13:00'],
        );

        $action = resolve(AssertMinimumAvailabilityAction::class);

        $this->expectException(ValidationException::class);

        $action->execute(
            $team,
            now()->toDateString(),
            '12:00',
            '13:00',
            $secondUser->id,
        );
    }
}
