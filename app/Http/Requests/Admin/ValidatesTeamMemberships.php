<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Site;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

trait ValidatesTeamMemberships
{
    /** @return array<string, mixed> */
    protected function teamMembershipRules(): array
    {
        return [
            'team_memberships'                         => ['required', 'array', 'min:1'],
            'team_memberships.*.team_id'               => ['required', 'uuid'],
            'team_memberships.*.is_scheduled'          => ['required', 'boolean'],
            'team_memberships.*.schedule_visibility'   => ['required', Rule::in(['everyone', 'team', 'approvers', 'private'])],
            'team_memberships.*.is_time_off_approver'  => ['required', 'boolean'],
        ];
    }

    protected function validateTeamMemberships(Validator $validator, Site $site): void
    {
        $validator->after(function (Validator $validator) use ($site): void {
            $memberships = $this->input('team_memberships', []);
            $siteTeamIds = $site->teamIds();
            $seenTeamIds = [];

            foreach ($memberships as $index => $membership) {
                $teamId = $membership['team_id'] ?? null;

                if (! is_string($teamId) || ! in_array($teamId, $siteTeamIds, true)) {
                    $validator->errors()->add(
                        "team_memberships.{$index}.team_id",
                        'The selected team is invalid.',
                    );

                    continue;
                }

                if (in_array($teamId, $seenTeamIds, true)) {
                    $validator->errors()->add(
                        "team_memberships.{$index}.team_id",
                        'Each team can only be selected once.',
                    );
                }

                $seenTeamIds[] = $teamId;
            }
        });
    }

    /** @return array<string, mixed> */
    protected function siteMemberRules(Site $site): array
    {
        return [
            'working_hour_preset_id' => [
                'nullable',
                'uuid',
                Rule::exists('working_hour_presets', 'id')->where('site_id', $site->id),
            ],
            'time_off_approval' => ['required', Rule::in(['site_default', 'required', 'auto'])],
        ];
    }
}
