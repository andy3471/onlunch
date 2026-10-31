<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\DataTransferObjects\WorkingHourPresetData;
use App\Enums\TimeOffApprovalMode;
use App\Models\Site;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use App\Models\WorkingHourPreset;

class BuildUserFormAction
{
    use AsAction;

    /** @return array<string, mixed> */
    public function execute(Site $site, ?User $user = null): array
    {
        $presets = $site->workingHourPresets
            ->map(fn (WorkingHourPreset $preset) => WorkingHourPresetData::fromModel($preset))
            ->values()
            ->all();

        $siteMembership = $user instanceof User ? $user->siteMembershipFor($site) : null;

        $timeOffApproval = TimeOffApprovalMode::fromNullableBool(
            $siteMembership?->time_off_requires_approval,
        )->value;

        return [
            'presets'             => $presets,
            'teams'               => $site->teams()->orderBy('created_at')->get(['id', 'name']),
            'defaultTeamId'       => $site->defaultTeam()?->id,
            'workingHourPresetId' => $siteMembership?->working_hour_preset_id,
            'timeOffApproval'     => $user instanceof User ? $timeOffApproval : 'site_default',
            'teamMemberships'     => $user instanceof User
                ? $this->teamMembershipsForUser($site, $user)
                : $this->defaultTeamMemberships($site),
            'user'                => null,
        ];
    }

    /** @return list<array{teamId: string, isMember: bool, isScheduled: bool, scheduleVisibility: string, isTimeOffApprover: bool}> */
    protected function teamMembershipsForUser(Site $site, User $user): array
    {
        return $site->teams()
            ->orderBy('created_at')
            ->get()
            ->map(function (Team $team) use ($user): array {
                $membership = $user->membershipFor($team);

                return [
                    'teamId'             => $team->id,
                    'isMember'           => $membership instanceof TeamUser,
                    'isScheduled'        => $membership?->is_scheduled                ?? false,
                    'scheduleVisibility' => $membership?->schedule_visibility?->value ?? 'everyone',
                    'isTimeOffApprover'  => $membership?->is_time_off_approver        ?? false,
                ];
            })
            ->all();
    }

    /** @return list<array{teamId: string, isMember: bool, isScheduled: bool, scheduleVisibility: string, isTimeOffApprover: bool}> */
    protected function defaultTeamMemberships(Site $site): array
    {
        $defaultTeam = $site->defaultTeam();

        return $site->teams()
            ->orderBy('created_at')
            ->get()
            ->map(function (Team $team) use ($defaultTeam): array {
                $isDefault = $defaultTeam instanceof Team && $team->id === $defaultTeam->id;

                return [
                    'teamId'             => $team->id,
                    'isMember'           => $isDefault,
                    'isScheduled'        => $isDefault,
                    'scheduleVisibility' => 'everyone',
                    'isTimeOffApprover'  => false,
                ];
            })
            ->all();
    }
}
