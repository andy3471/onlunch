<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'site_id',
        'name',
        'tasks_enabled',
        'time_off_auto_approve',
        'minimum_available_staff',
        'default_task',
    ];

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** @return BelongsToMany<User, $this, TeamUser> */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(TeamUser::class)
            ->withPivot(['is_scheduled'])
            ->withTimestamps();
    }

    /** @return HasMany<Task, $this> */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /** @return array{start: string, end: string} */
    public function timelineBounds(): array
    {
        return $this->site->timelineBounds();
    }

    /** @return HasMany<TimeBlock, $this> */
    public function timeBlocks(): HasMany
    {
        return $this->hasMany(TimeBlock::class);
    }

    /** @return HasMany<TaskAssignment, $this> */
    public function taskAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /** @return HasMany<TimeOffRequest, $this> */
    public function timeOffRequests(): HasMany
    {
        return $this->hasMany(TimeOffRequest::class);
    }

    /** @return HasMany<LunchBooking, $this> */
    public function lunchBookings(): HasMany
    {
        return $this->hasMany(LunchBooking::class);
    }

    public function hasMinimumAvailabilityRule(): bool
    {
        return $this->minimum_available_staff !== null && $this->minimum_available_staff > 0;
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'tasks_enabled'         => 'boolean',
            'time_off_auto_approve' => 'boolean',
        ];
    }
}
