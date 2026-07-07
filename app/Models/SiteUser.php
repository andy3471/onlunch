<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SiteUser extends Pivot
{
    public $incrementing = true;

    protected $table = 'site_user';

    protected $fillable = [
        'site_id',
        'user_id',
        'is_site_admin',
        'working_hour_preset_id',
        'onboarding_completed_at',
        'time_off_requires_approval',
    ];

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<WorkingHourPreset, $this> */
    public function workingHourPreset(): BelongsTo
    {
        return $this->belongsTo(WorkingHourPreset::class);
    }

    public function hasCompletedOnboarding(): bool
    {
        return $this->onboarding_completed_at !== null
            && $this->working_hour_preset_id  !== null;
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_site_admin'              => 'boolean',
            'onboarding_completed_at'    => 'datetime',
            'time_off_requires_approval' => 'boolean',
        ];
    }
}
