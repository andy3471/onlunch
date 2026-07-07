<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\Site;
use App\Models\Team;
use App\Models\User;
use App\Models\WorkingHourPreset;
use Illuminate\Support\Facades\Date;

trait CreatesSiteWithTeam
{
    /** @param  array<string, mixed>  $siteAttributes
     * @param  array<string, mixed>  $teamAttributes
     * @return array{0: Site, 1: Team}
     */
    protected function createSiteWithTeam(array $siteAttributes = [], array $teamAttributes = []): array
    {
        $site = Site::factory()->create($siteAttributes);
        $team = Team::factory()->for($site)->create(array_merge(['name' => 'Everyone'], $teamAttributes));

        WorkingHourPreset::create([
            'site_id'    => $site->id,
            'name'       => 'Standard',
            'start_time' => '09:00:00',
            'end_time'   => '17:00:00',
            'sort_order' => 0,
        ]);

        return [$site, $team];
    }

    /** @param  array{is_site_admin?: bool, is_scheduled?: bool, time_off_requires_approval?: bool|null, schedule_visibility?: string, is_time_off_approver?: bool}  $options */
    protected function attachOnboardedMember(
        Site $site,
        Team $team,
        User $user,
        array $options = [],
    ): WorkingHourPreset {
        $isSiteAdmin             = $options['is_site_admin']              ?? false;
        $isScheduled             = $options['is_scheduled']               ?? true;
        $timeOffRequiresApproval = $options['time_off_requires_approval'] ?? null;
        $scheduleVisibility      = $options['schedule_visibility']        ?? 'everyone';
        $isTimeOffApprover       = $options['is_time_off_approver']       ?? false;

        $preset = $site->workingHourPresets()->first() ?? WorkingHourPreset::create([
            'site_id'    => $site->id,
            'name'       => 'Standard',
            'start_time' => '09:00:00',
            'end_time'   => '17:00:00',
            'sort_order' => 0,
        ]);

        $site->members()->attach($user->id, [
            'is_site_admin'              => $isSiteAdmin,
            'working_hour_preset_id'     => $preset->id,
            'onboarding_completed_at'    => Date::now(),
            'time_off_requires_approval' => $timeOffRequiresApproval,
        ]);

        $team->members()->attach($user->id, [
            'is_scheduled'         => $isScheduled,
            'schedule_visibility'  => $scheduleVisibility,
            'is_time_off_approver' => $isTimeOffApprover,
        ]);

        return $preset;
    }

    protected function attachSiteMember(Site $site, Team $team, User $user, array $options = []): void
    {
        $isSiteAdmin = $options['is_site_admin'] ?? false;
        $isScheduled = $options['is_scheduled']  ?? false;

        $site->members()->attach($user->id, ['is_site_admin' => $isSiteAdmin]);
        $team->members()->attach($user->id, ['is_scheduled' => $isScheduled]);
    }

    /** @param  array{is_scheduled?: bool}  $options */
    protected function attachToTeam(Team $team, User $user, array $options = []): void
    {
        $isScheduled = $options['is_scheduled'] ?? true;

        $team->members()->attach($user->id, ['is_scheduled' => $isScheduled]);
    }
}
