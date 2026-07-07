<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TimeOffRequestStatus;
use App\Models\Concerns\HasTimeBlock;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeOffRequest extends Model
{
    use HasTimeBlock;
    use HasUuids;

    protected $fillable = [
        'team_id',
        'user_id',
        'status',
        'notes',
        'reviewed_by',
        'reviewed_at',
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

    /** @return BelongsTo<User, $this> */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status'       => TimeOffRequestStatus::class,
            'reviewed_at'  => 'datetime',
        ];
    }
}
