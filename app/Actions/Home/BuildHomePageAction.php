<?php

declare(strict_types=1);

namespace App\Actions\Home;

use App\Actions\Concerns\AsAction;
use App\Actions\Schedule\CanViewUserScheduleAction;
use App\DataTransferObjects\HomePageData;
use App\DataTransferObjects\ScheduleUserData;
use App\DataTransferObjects\TaskOptionData;
use App\DataTransferObjects\TimeBlockData;
use App\DataTransferObjects\WorkingHourPresetData;
use App\DataTransferObjects\WorkingHoursData;
use App\Enums\TimeOffRequestStatus;
use App\Models\LunchBooking;
use App\Models\Site;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TimeBlock;
use App\Models\TimeOffRequest;
use App\Models\User;
use Carbon\CarbonInterface;

class BuildHomePageAction
{
    use AsAction;

    public function __construct(
        private readonly CanViewUserScheduleAction $canViewUserSchedule,
    ) {}

    public function execute(Site $site, CarbonInterface $date, ?User $user): HomePageData
    {
        $dateString = $date->toDateString();
        $teamIds    = $site->teamIds();

        $blocks = TimeBlock::query()
            ->whereIn('team_id', $teamIds)
            ->where('date', $dateString)
            ->with([
                'user',
                'blockable' => fn ($morphTo) => $morphTo->morphWith([
                    TaskAssignment::class => ['task'],
                ]),
            ])
            ->get()
            ->filter(function (TimeBlock $block) use ($site, $user): bool {
                if ($user instanceof User && ! $this->canViewUserSchedule->execute($site, $user, $block->user)) {
                    return false;
                }

                if ($block->blockable_type !== TimeOffRequest::class) {
                    return true;
                }

                $status = $block->blockable->status;

                if ($status === TimeOffRequestStatus::Approved) {
                    return true;
                }

                return $user instanceof User && $block->user_id === $user->id;
            });

        $myLunchBooking    = null;
        $myTaskAssignments = [];

        if ($user instanceof User) {
            $lunchBlock = $blocks->first(function (TimeBlock $block) use ($user): bool {
                return $block->blockable_type === LunchBooking::class
                    && $block->user_id        === $user->id;
            });

            if ($lunchBlock !== null) {
                $myLunchBooking = TimeBlockData::from($lunchBlock);
            }

            $myTaskAssignments = TimeBlockData::collect(
                $blocks
                    ->filter(fn (TimeBlock $block): bool => $block->blockable_type === TaskAssignment::class
                        && $block->user_id                                         === $user->id)
                    ->values(),
            );
        }

        $myWorkingHours = null;

        if ($user instanceof User) {
            $preset = $user->workingHourPresetForSite($site);

            if ($preset instanceof \App\Models\WorkingHourPreset) {
                $myWorkingHours = WorkingHourPresetData::from([
                    'id'        => $preset->id,
                    'name'      => $preset->name,
                    'startTime' => mb_substr((string) $preset->getRawOriginal('start_time'), 0, 5),
                    'endTime'   => mb_substr((string) $preset->getRawOriginal('end_time'), 0, 5),
                    'label'     => $preset->label(),
                ]);
            }
        }

        $tasks = Task::query()
            ->whereIn('team_id', $teamIds)
            ->orderBy('name')
            ->get()
            ->unique('name')
            ->values();

        return HomePageData::from([
            'timeBlocks'        => TimeBlockData::collect($blocks->values()),
            'workingHours'      => WorkingHoursData::from($site->timelineBounds()),
            'myLunchBooking'    => $myLunchBooking,
            'myTaskAssignments' => $myTaskAssignments,
            'selectedDate'      => $dateString,
            'tasks'             => TaskOptionData::collect(
                $tasks->map(fn (Task $task): TaskOptionData => TaskOptionData::from([
                    'id'    => $task->id,
                    'name'  => $task->name,
                    'color' => $task->color,
                ])),
            ),
            'myWorkingHours' => $myWorkingHours,
            'scheduleUsers'  => ScheduleUserData::collect($this->scheduledSiteMembers($site, $user)),
        ]);
    }

    /** @return list<ScheduleUserData> */
    protected function scheduledSiteMembers(Site $site, ?User $viewer): array
    {
        return $site->members()
            ->whereHas('teams', function ($query) use ($site): void {
                $query->whereIn('teams.id', $site->teamIds())
                    ->where('team_user.is_scheduled', true);
            })
            ->orderBy('name')
            ->get(['users.id', 'users.name'])
            ->filter(fn (User $member): bool => $this->canViewUserSchedule->execute($site, $viewer, $member))
            ->map(fn (User $member): ScheduleUserData => ScheduleUserData::from([
                'userId'   => $member->id,
                'userName' => $member->name,
            ]))
            ->values()
            ->all();
    }
}
