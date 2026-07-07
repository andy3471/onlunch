<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class HomePageData extends Data
{
    public function __construct(
        #[DataCollectionOf(TimeBlockData::class)]
        public DataCollection $timeBlocks,
        public WorkingHoursData $workingHours,
        public ?TimeBlockData $myLunchBooking,
        #[DataCollectionOf(TimeBlockData::class)]
        public DataCollection $myTaskAssignments,
        public string $selectedDate,
        #[DataCollectionOf(TaskOptionData::class)]
        public DataCollection $tasks,
        public ?WorkingHourPresetData $myWorkingHours,
        #[DataCollectionOf(ScheduleUserData::class)]
        public DataCollection $scheduleUsers,
    ) {}
}
