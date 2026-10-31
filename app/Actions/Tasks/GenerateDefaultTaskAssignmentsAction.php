<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Actions\Concerns\AsAction;
use App\Models\TaskAssignment;
use App\Models\Team;
use App\Support\TimeInput;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Closure;

class GenerateDefaultTaskAssignmentsAction
{
    use AsAction;

    /** @param  (Closure(string): void)|null  $report */
    public function execute(
        ?CarbonImmutable $startDate = null,
        ?CarbonImmutable $endDate = null,
        ?Closure $report = null,
    ): void {
        $startDate ??= CarbonImmutable::now();
        $endDate   ??= CarbonImmutable::now()->addWeek();
        $dateRange = CarbonPeriod::create($startDate, $endDate);
        $log       = $report ?? static function (string $message): void {};

        Team::query()->each(function (Team $team) use ($dateRange, $log): void {
            $log("Processing team: {$team->name}");

            if ($team->default_task === 'none') {
                $log("No default task set for team: {$team->name}");

                return;
            }

            $defaultTask = $team->tasks()->where('name', $team->default_task)->first();

            if ($defaultTask === null) {
                $log("No matching default task found for team: {$team->name}");

                return;
            }

            $log("The default task is {$defaultTask->name}");

            foreach ($dateRange as $date) {
                if (! $date->isWeekday()) {
                    $log("{$date} Is Weekend");

                    continue;
                }

                $log((string) $date);
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
                            'start' => TimeInput::forStorage($preset->start_time),
                            'end'   => TimeInput::forStorage($preset->end_time),
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

                    $log("{$user->name} given task {$defaultTask->name} for {$dateString}");
                }
            }
        });
    }
}
