<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasTimeBlock;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LunchBooking extends Model
{
    use HasTimeBlock;
    use HasUuids;

    protected $fillable = [
        'team_id',
        'user_id',
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
}
