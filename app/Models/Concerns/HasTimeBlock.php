<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\TimeBlock;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

trait HasTimeBlock
{
    /**
     * @param  array<string, mixed>  $parentAttrs
     * @param  array{date: string, start_time: string, end_time: string}  $timeAttrs
     */
    public static function createWithTimeBlock(array $parentAttrs, array $timeAttrs): static
    {
        throw_unless(isset($parentAttrs['team_id'], $parentAttrs['user_id']), InvalidArgumentException::class, 'team_id and user_id are required.');

        return DB::transaction(function () use ($parentAttrs, $timeAttrs) {
            /** @var static $parent */
            $parent = static::create($parentAttrs);

            $parent->timeBlock()->create([
                'team_id'    => $parentAttrs['team_id'],
                'user_id'    => $parentAttrs['user_id'],
                'date'       => $timeAttrs['date'],
                'start_time' => $timeAttrs['start_time'],
                'end_time'   => $timeAttrs['end_time'],
            ]);

            return $parent->load('timeBlock');
        });
    }

    public static function bootHasTimeBlock(): void
    {
        static::deleting(function (self $model): void {
            $model->timeBlock?->delete();
        });
    }

    /** @return MorphOne<TimeBlock, $this> */
    public function timeBlock(): MorphOne
    {
        return $this->morphOne(TimeBlock::class, 'blockable');
    }
}
