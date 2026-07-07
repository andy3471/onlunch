<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkingHourPreset extends Model
{
    use HasUuids;

    protected $fillable = [
        'site_id',
        'name',
        'start_time',
        'end_time',
        'sort_order',
    ];

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** @return HasMany<SiteUser, $this> */
    public function memberships(): HasMany
    {
        return $this->hasMany(SiteUser::class);
    }

    public function label(): string
    {
        $start = mb_substr((string) $this->getRawOriginal('start_time'), 0, 5);
        $end   = mb_substr((string) $this->getRawOriginal('end_time'), 0, 5);

        return "{$this->name} ({$start}–{$end})";
    }
}
