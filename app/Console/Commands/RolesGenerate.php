<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\RoleAssignment;
use App\Models\Team;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class RolesGenerate extends Command
{
    protected $signature = 'roles:generate';

    protected $description = 'Generate default role assignments for scheduled users';

    public function handle(): void
    {
        $startDate = now();
        $endDate   = now()->addWeek();
        $dateRange = CarbonPeriod::create($startDate, $endDate);

        Team::all()->each(function (Team $team) use ($dateRange): void {
            $this->line("Processing team: {$team->name}");

            if ($team->default_role === 'none') {
                $this->line("No default role set for team: {$team->name}");

                return;
            }

            $defaultRole = $team->roles()->where('name', $team->default_role)->first();

            if (! $defaultRole) {
                $this->warn("No matching default role found for team: {$team->name}");

                return;
            }

            $this->line("The Default Role is {$defaultRole->name}");

            foreach ($dateRange as $date) {
                if (! $date->isWeekday()) {
                    $this->line("{$date} Is Weekend");

                    continue;
                }

                $this->line((string) $date);
                $dateString = CarbonImmutable::parse($date)->toDateString();

                $usersWithAssignments = RoleAssignment::query()
                    ->where('team_id', $team->id)
                    ->whereHas('timeBlock', fn ($query) => $query->where('date', $dateString))
                    ->pluck('user_id')
                    ->all();

                $users = $team->members()
                    ->whereNotIn('users.id', $usersWithAssignments)
                    ->get();

                foreach ($users as $user) {
                    if (! $user->is_scheduled) {
                        $this->line("{$user->name} is not a scheduled user");

                        continue;
                    }

                    RoleAssignment::createWithTimeBlock(
                        [
                            'team_id' => $team->id,
                            'user_id' => $user->id,
                            'role_id' => $defaultRole->id,
                        ],
                        [
                            'date'       => $dateString,
                            'start_time' => $team->work_day_start,
                            'end_time'   => $team->work_day_end,
                        ],
                    );

                    $this->line("{$user->name} Given Role Of {$defaultRole->name} For {$dateString}");
                }
            }
        });
    }
}
