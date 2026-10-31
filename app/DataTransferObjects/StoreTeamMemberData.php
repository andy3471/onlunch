<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class StoreTeamMemberData extends Data
{
    /** @param  list<array{teamId: string, isMember: bool, isScheduled: bool, scheduleVisibility: string, isTimeOffApprover: bool}>  $teamMemberships */
    public function __construct(
        public string $email,
        public bool $isAdmin,
        public array $teamMemberships,
        public ?string $name = null,
        public ?string $password = null,
        public ?string $workingHourPresetId = null,
        public string $timeOffApproval = 'site_default',
    ) {}

    /** @param  array<string, mixed>  $payload */
    public static function fromValidated(array $payload): self
    {
        return new self(
            email: (string) $payload['email'],
            isAdmin: (bool) $payload['is_admin'],
            teamMemberships: array_values($payload['team_memberships'] ?? []),
            name: isset($payload['name']) ? (string) $payload['name'] : null,
            password: isset($payload['password']) ? (string) $payload['password'] : null,
            workingHourPresetId: isset($payload['working_hour_preset_id'])
                ? (string) $payload['working_hour_preset_id']
                : null,
            timeOffApproval: (string) ($payload['time_off_approval'] ?? 'site_default'),
        );
    }

    /** @return array<string, mixed> */
    public function toActionArray(): array
    {
        return [
            'name'                    => $this->name,
            'email'                   => $this->email,
            'password'                => $this->password,
            'is_admin'                => $this->isAdmin,
            'working_hour_preset_id'  => $this->workingHourPresetId,
            'time_off_approval'       => $this->timeOffApproval,
            'team_memberships'        => $this->teamMemberships,
        ];
    }
}
