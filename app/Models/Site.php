<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'register_enabled',
        'reset_password_enabled',
    ];

    /** @return BelongsToMany<User, $this, SiteUser> */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(SiteUser::class)
            ->withPivot([
                'is_site_admin',
                'working_hour_preset_id',
                'onboarding_completed_at',
                'time_off_requires_approval',
            ])
            ->withTimestamps();
    }

    /** @return HasMany<Team, $this> */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /** @return HasMany<WorkingHourPreset, $this> */
    public function workingHourPresets(): HasMany
    {
        return $this->hasMany(WorkingHourPreset::class)->orderBy('sort_order');
    }

    /** @return array{start: string, end: string} */
    public function timelineBounds(): array
    {
        return $this->boundsFromPresets($this->workingHourPresets);
    }

    /** @return list<string> */
    public function teamIds(): array
    {
        return $this->teams()->pluck('id')->all();
    }

    public function defaultTeam(): ?Team
    {
        return $this->teams()->orderBy('created_at')->first();
    }

    /** @param  list<string>  $teamIds
     * @return array{start: string, end: string}
     */
    public function timelineBoundsForTeams(array $teamIds): array
    {
        return $this->timelineBounds();
    }

    /** @param  \Illuminate\Support\Collection<int, WorkingHourPreset>|\Illuminate\Database\Eloquent\Collection<int, WorkingHourPreset>  $presets
     * @return array{start: string, end: string}
     */
    protected function boundsFromPresets($presets): array
    {
        if ($presets->isEmpty()) {
            return ['start' => '09:00', 'end' => '17:00'];
        }

        $starts = $presets->map(fn (WorkingHourPreset $preset): string => mb_substr((string) $preset->getRawOriginal('start_time'), 0, 5));
        $ends   = $presets->map(fn (WorkingHourPreset $preset): string => mb_substr((string) $preset->getRawOriginal('end_time'), 0, 5));

        return [
            'start' => $starts->min(),
            'end'   => $ends->max(),
        ];
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'register_enabled'       => 'boolean',
            'reset_password_enabled' => 'boolean',
        ];
    }
}
