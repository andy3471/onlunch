<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasTimeBlock;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskAssignment extends Model
{
    use HasTimeBlock;
    use HasUuids;

    protected $fillable = [
        'team_id',
        'user_id',
        'task_id',
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

    /** @return BelongsTo<Task, $this> */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function isAway(): bool
    {
        return $this->task_id === null;
    }
}
