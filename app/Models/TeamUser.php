<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ScheduleVisibility;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot
{
    public $incrementing = true;

    protected $table = 'team_user';

    protected $fillable = [
        'team_id',
        'user_id',
        'is_scheduled',
        'schedule_visibility',
        'is_time_off_approver',
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

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_scheduled'         => 'boolean',
            'is_time_off_approver' => 'boolean',
            'schedule_visibility'  => ScheduleVisibility::class,
        ];
    }
}
