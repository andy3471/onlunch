<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Enums\TimeOffRequestStatus;
use App\Models\LunchBooking;
use App\Models\TaskAssignment;
use App\Models\TimeBlock;
use App\Models\TimeOffRequest;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TimeBlockData extends Data
{
    public function __construct(
        public string $id,
        public string $type,
        public string $userId,
        public string $userName,
        public string $date,
        public string $startTime,
        public string $endTime,
        public ?string $taskName = null,
        public ?string $taskColor = null,
        public ?string $status = null,
        public ?string $notes = null,
        public ?string $assignmentId = null,
        public ?string $taskId = null,
        public ?string $lunchBookingId = null,
    ) {}

    public static function fromTimeBlock(TimeBlock $block): self
    {
        $blockable = $block->blockable;

        $type = match ($block->blockable_type) {
            TaskAssignment::class => 'task_assignment',
            TimeOffRequest::class => 'time_off_request',
            LunchBooking::class   => 'lunch_booking',
            default               => 'unknown',
        };

        $taskName       = null;
        $taskColor      = null;
        $status         = null;
        $notes          = null;
        $assignmentId   = null;
        $taskId         = null;
        $lunchBookingId = null;

        if ($blockable instanceof TaskAssignment) {
            $taskName     = $blockable->task?->name ?? 'Away';
            $taskColor    = $blockable->task?->color;
            $assignmentId = $blockable->id;
            $taskId       = $blockable->task_id;
        }

        if ($blockable instanceof TimeOffRequest) {
            $status = $blockable->status instanceof TimeOffRequestStatus
                ? $blockable->status->value
                : (string) $blockable->status;
            $notes = $blockable->notes;
        }

        if ($blockable instanceof LunchBooking) {
            $lunchBookingId = $blockable->id;
        }

        return self::from([
            'id'             => $block->id,
            'type'           => $type,
            'userId'         => $block->user_id,
            'userName'       => $block->user->name,
            'date'           => $block->date->toDateString(),
            'startTime'      => mb_substr((string) $block->getRawOriginal('start_time'), 0, 5),
            'endTime'        => mb_substr((string) $block->getRawOriginal('end_time'), 0, 5),
            'taskName'       => $taskName,
            'taskColor'      => $taskColor,
            'status'         => $status,
            'notes'          => $notes,
            'assignmentId'   => $assignmentId,
            'taskId'         => $taskId,
            'lunchBookingId' => $lunchBookingId,
        ]);
    }

    public static function from(mixed ...$payloads): static
    {
        if (count($payloads) === 1 && $payloads[0] instanceof TimeBlock) {
            return self::fromTimeBlock($payloads[0]);
        }

        return parent::from(...$payloads);
    }
}
