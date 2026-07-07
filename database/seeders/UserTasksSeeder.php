<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Site;
use App\Models\TaskAssignment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class UserTasksSeeder extends Seeder
{
    public function run(): void
    {
        $site   = Site::where('slug', 'demo')->firstOrFail();
        $team   = $site->teams()->firstOrFail();
        $tasks  = $team->tasks;
        $preset = $site->workingHourPresets()->first();

        $startDate = Carbon::now()->addMonth(-1);
        $endDate   = Carbon::now()->addMonth(1);
        $dateRange = CarbonPeriod::create($startDate, $endDate);

        foreach ($dateRange as $date) {
            if (! $date->isWeekday()) {
                continue;
            }

            $dateString = Carbon::parse($date)->toDateString();

            foreach ($team->members as $user) {
                $task = $tasks->random();

                TaskAssignment::createWithTimeBlock(
                    [
                        'team_id' => $team->id,
                        'user_id' => $user->id,
                        'task_id' => $task->id,
                    ],
                    [
                        'date'       => $dateString,
                        'start_time' => $preset?->getRawOriginal('start_time') ?? '09:00:00',
                        'end_time'   => $preset?->getRawOriginal('end_time')   ?? '17:00:00',
                    ],
                );
            }
        }
    }
}
