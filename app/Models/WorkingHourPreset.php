<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkingHourPreset extends Model
{
    /** @use HasFactory<\Database\Factories\WorkingHourPresetFactory> */
    use HasFactory;

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
        return "{$this->name} ({$this->start_time}–{$this->end_time})";
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
}
