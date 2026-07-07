<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TaskAssignment;
use App\Models\Team;
use App\Support\TimeInput;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class TasksGenerate extends Command
{
    protected $signature = 'tasks:generate';

    protected $description = 'Generate default task assignments for scheduled users';

    public function handle(): void
    {
        $startDate = now();
        $endDate   = now()->addWeek();
        $dateRange = CarbonPeriod::create($startDate, $endDate);

        Team::all()->each(function (Team $team) use ($dateRange): void {
            $this->line("Processing team: {$team->name}");

            if ($team->default_task === 'none') {
                $this->line("No default task set for team: {$team->name}");

                return;
            }

            $defaultTask = $team->tasks()->where('name', $team->default_task)->first();

            if (! $defaultTask) {
                $this->warn("No matching default task found for team: {$team->name}");

                return;
            }

            $this->line("The default task is {$defaultTask->name}");

            foreach ($dateRange as $date) {
                if (! $date->isWeekday()) {
                    $this->line("{$date} Is Weekend");

                    continue;
                }

                $this->line((string) $date);
                $dateString = CarbonImmutable::parse($date)->toDateString();

                $usersWithAssignments = TaskAssignment::query()
                    ->where('team_id', $team->id)
                    ->whereHas('timeBlock', fn ($query) => $query->where('date', $dateString))
                    ->pluck('user_id')
                    ->all();

                $users = $team->members()
                    ->wherePivot('is_scheduled', true)
                    ->whereNotIn('users.id', $usersWithAssignments)
                    ->get();

                foreach ($users as $user) {
                    $preset = $user->workingHourPresetForSite($team->site);
                    $bounds = $preset
                        ? [
                            'start' => TimeInput::forStorage(TimeInput::normalize((string) $preset->getRawOriginal('start_time')) ?? '09:00'),
                            'end'   => TimeInput::forStorage(TimeInput::normalize((string) $preset->getRawOriginal('end_time')) ?? '17:00'),
                        ]
                        : $team->timelineBounds();

                    TaskAssignment::createWithTimeBlock(
                        [
                            'team_id' => $team->id,
                            'user_id' => $user->id,
                            'task_id' => $defaultTask->id,
                        ],
                        [
                            'date'       => $dateString,
                            'start_time' => TimeInput::forStorage($bounds['start']),
                            'end_time'   => TimeInput::forStorage($bounds['end']),
                        ],
                    );

                    $this->line("{$user->name} given task {$defaultTask->name} for {$dateString}");
                }
            }
        });
    }
}
