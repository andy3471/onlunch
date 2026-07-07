<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\LunchBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LunchBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_book_lunch_when_none_exists(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $response = $this->actingAs($user)->post(route('lunch-booking.store', ['tenant' => 'acme']), [
            'start_time' => '12:00',
            'end_time'   => '13:00',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('lunch_bookings', [
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('time_blocks', [
            'team_id'    => $team->id,
            'user_id'    => $user->id,
            'date'       => now()->toDateString(),
            'start_time' => '12:00:00',
            'end_time'   => '13:00:00',
        ]);
    }

    public function test_user_can_update_lunch_booking_times(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $booking = LunchBooking::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id],
            ['date' => now()->toDateString(), 'start_time' => '12:00', 'end_time' => '13:00'],
        );

        $response = $this->actingAs($user)->put(route('lunch-booking.update', [
            'tenant'       => 'acme',
            'lunchBooking' => $booking->id,
        ]), [
            'start_time' => '12:37',
            'end_time'   => '13:22',
        ]);

        $response->assertRedirect();
        $this->assertSame('12:37', $booking->refresh()->timeBlock->start_time);
        $this->assertSame('13:22', $booking->timeBlock->end_time);
    }
}
