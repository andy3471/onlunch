<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use HasUuids;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_approved',
        'is_scheduled',
    ];

    protected $appends = [
        'deleted',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @return BelongsToMany<Site, $this, SiteUser> */
    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class)
            ->using(SiteUser::class)
            ->withPivot([
                'is_site_admin',
                'working_hour_preset_id',
                'onboarding_completed_at',
                'time_off_requires_approval',
            ])
            ->withTimestamps();
    }

    /** @return BelongsToMany<Team, $this, TeamUser> */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->using(TeamUser::class)
            ->withPivot([
                'is_scheduled',
                'schedule_visibility',
                'is_time_off_approver',
            ])
            ->withTimestamps();
    }

    public function siteMembershipFor(Site $site): ?SiteUser
    {
        $membership = $this->sites()
            ->where('sites.id', $site->id)
            ->first()?->pivot;

        return $membership instanceof SiteUser ? $membership : null;
    }

    public function isSiteAdminFor(Site $site): bool
    {
        return $this->siteMembershipFor($site)?->is_site_admin ?? false;
    }

    public function membershipFor(Team $team): ?TeamUser
    {
        $membership = $this->teams()
            ->where('teams.id', $team->id)
            ->first()?->pivot;

        return $membership instanceof TeamUser ? $membership : null;
    }

    public function primaryTeamFor(Site $site): ?Team
    {
        return $this->teams()
            ->where('site_id', $site->id)
            ->orderBy('teams.created_at')
            ->first();
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Team> */
    public function teamsForSite(Site $site): \Illuminate\Database\Eloquent\Collection
    {
        return $this->teams()
            ->where('site_id', $site->id)
            ->get();
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Team> */
    public function accessibleTeamsFor(Site $site): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->isSiteAdminFor($site)) {
            return $site->teams()->orderBy('name')->get();
        }

        return $this->teamsForSite($site)->sortBy('name')->values();
    }

    public function workingHourPresetForSite(Site $site): ?WorkingHourPreset
    {
        return $this->siteMembershipFor($site)?->workingHourPreset;
    }

    public function hasCompletedOnboardingFor(Site $site): bool
    {
        return $this->siteMembershipFor($site)?->hasCompletedOnboarding() ?? false;
    }

    public function requiresTimeOffApproval(Site $site): bool
    {
        $membership = $this->siteMembershipFor($site);

        if ($membership?->time_off_requires_approval !== null) {
            return $membership->time_off_requires_approval;
        }

        $team = $this->primaryTeamFor($site);

        return $team instanceof Team ? ! $team->time_off_auto_approve : true;
    }

    public function canApproveTimeOffFor(Team $team): bool
    {
        if ($this->isSiteAdminFor($team->site)) {
            return true;
        }

        return (bool) $this->membershipFor($team)?->is_time_off_approver;
    }

    public function canAccessTimeOffManagement(Site $site): bool
    {
        if ($this->isSiteAdminFor($site)) {
            return true;
        }

        return $this->teams()
            ->where('site_id', $site->id)
            ->wherePivot('is_time_off_approver', true)
            ->exists();
    }

    /** @return list<string> */
    public function approverTeamIdsFor(Site $site): array
    {
        if ($this->isSiteAdminFor($site)) {
            return $site->teamIds();
        }

        return $this->teams()
            ->where('site_id', $site->id)
            ->wherePivot('is_time_off_approver', true)
            ->pluck('teams.id')
            ->all();
    }

    public function canManageTaskAssignment(TaskAssignment $assignment): bool
    {
        if ($this->id === $assignment->user_id) {
            return true;
        }

        $assignment->loadMissing('team.site');

        return $this->isSiteAdminFor($assignment->team->site);
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

    protected function isDeleted(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                return $this->deleted_at !== null;
            }
        );
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_admin'          => 'boolean',
            'is_approved'       => 'boolean',
            'is_scheduled'      => 'boolean',
        ];
    }
}
