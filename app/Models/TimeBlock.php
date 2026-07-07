<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TimeOffRequestStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use InvalidArgumentException;

class TimeBlock extends Model
{
    use HasUuids;

    protected $fillable = [
        'team_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'blockable_type',
        'blockable_id',
    ];

    /** @return BelongsTo<Team, $this> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return MorphTo<Model, $this> */
    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        static::saving(function (TimeBlock $block): void {
            throw_if($block->start_time >= $block->end_time, InvalidArgumentException::class, 'start_time must be before end_time.');
        });
    }

    /** @param Builder<TimeBlock> $query */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function overlapping(Builder $query, string $startTime, string $endTime): Builder
    {
        return $query
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);
    }

    /** @param Builder<TimeBlock> $query */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function countsForSchedule(Builder $query): Builder
    {
        return $query->where(function (Builder $overlapQuery): void {
            $overlapQuery
                ->where('blockable_type', '!=', TimeOffRequest::class)
                ->orWhereHasMorph(
                    'blockable',
                    [TimeOffRequest::class],
                    fn (Builder $timeOffQuery): Builder => $timeOffQuery->where(
                        'status',
                        '!=',
                        TimeOffRequestStatus::Rejected->value,
                    ),
                );
        });
    }

    /** @param Builder<TimeBlock> $query */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forTeam(Builder $query, Team $team): Builder
    {
        return $query->where('team_id', $team->id);
    }

    /** @param Builder<TimeBlock> $query */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forDate(Builder $query, string $date): Builder
    {
        return $query->where('date', $date);
    }

    /** @param Builder<TimeBlock> $query */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    /** @return Attribute<string, never> */
    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value): string => mb_substr((string) $value, 0, 5),
        );
    }

    /** @return Attribute<string, never> */
    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value): string => mb_substr((string) $value, 0, 5),
        );
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
