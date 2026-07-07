<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $team_id
 * @property string $user_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Team $team
 * @property-read \App\Models\TimeBlock|null $timeBlock
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LunchBooking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LunchBooking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LunchBooking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LunchBooking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LunchBooking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LunchBooking whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LunchBooking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LunchBooking whereUserId($value)
 */
	class LunchBooking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property bool $register_enabled
 * @property bool $reset_password_enabled
 * @property-read \App\Models\SiteUser|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkingHourPreset> $workingHourPresets
 * @property-read int|null $working_hour_presets_count
 * @method static \Database\Factories\SiteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereRegisterEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereResetPasswordEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereUpdatedAt($value)
 */
	class Site extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $site_id
 * @property string $user_id
 * @property bool $is_site_admin
 * @property string|null $working_hour_preset_id
 * @property \Carbon\CarbonImmutable|null $onboarding_completed_at
 * @property bool|null $time_off_requires_approval
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Site $site
 * @property-read \App\Models\User $user
 * @property-read \App\Models\WorkingHourPreset|null $workingHourPreset
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereIsSiteAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereOnboardingCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereTimeOffRequiresApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteUser whereWorkingHourPresetId($value)
 */
	class SiteUser extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $team_id
 * @property string $name
 * @property string|null $color
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskAssignment> $taskAssignments
 * @property-read int|null $task_assignments_count
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereUpdatedAt($value)
 */
	class Task extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $team_id
 * @property string $user_id
 * @property string|null $task_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Task|null $task
 * @property-read \App\Models\Team $team
 * @property-read \App\Models\TimeBlock|null $timeBlock
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskAssignment whereUserId($value)
 */
	class TaskAssignment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $site_id
 * @property string $name
 * @property bool $tasks_enabled
 * @property bool $time_off_auto_approve
 * @property int|null $minimum_available_staff
 * @property string $default_task
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LunchBooking> $lunchBookings
 * @property-read int|null $lunch_bookings_count
 * @property-read \App\Models\TeamUser|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \App\Models\Site $site
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskAssignment> $taskAssignments
 * @property-read int|null $task_assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeBlock> $timeBlocks
 * @property-read int|null $time_blocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeOffRequest> $timeOffRequests
 * @property-read int|null $time_off_requests_count
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereDefaultTask($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereMinimumAvailableStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereTasksEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereTimeOffAutoApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $team_id
 * @property string $user_id
 * @property bool $is_scheduled
 * @property \App\Enums\ScheduleVisibility $schedule_visibility
 * @property bool $is_time_off_approver
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Team $team
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser whereIsScheduled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser whereIsTimeOffApprover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser whereScheduleVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamUser whereUserId($value)
 */
	class TeamUser extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $team_id
 * @property string $user_id
 * @property \Carbon\CarbonImmutable $date
 * @property string $start_time
 * @property string $end_time
 * @property string $blockable_type
 * @property string $blockable_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model $blockable
 * @property-read \App\Models\Team $team
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock countsForSchedule()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock forDate(string $date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock forTeam(\App\Models\Team $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock forUser(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock overlapping(string $startTime, string $endTime)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereBlockableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereBlockableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeBlock whereUserId($value)
 */
	class TimeBlock extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $team_id
 * @property string $user_id
 * @property \App\Enums\TimeOffRequestStatus $status
 * @property string|null $notes
 * @property string|null $reviewed_by
 * @property \Carbon\CarbonImmutable|null $reviewed_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $reviewer
 * @property-read \App\Models\Team $team
 * @property-read \App\Models\TimeBlock|null $timeBlock
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeOffRequest whereUserId($value)
 */
	class TimeOffRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string $password
 * @property bool $is_admin
 * @property bool $is_approved
 * @property bool $is_scheduled
 * @property string|null $remember_token
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read bool $is_deleted
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LunchBooking> $lunchBookings
 * @property-read int|null $lunch_bookings_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\TeamUser|\App\Models\SiteUser|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Site> $sites
 * @property-read int|null $sites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskAssignment> $taskAssignments
 * @property-read int|null $task_assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeBlock> $timeBlocks
 * @property-read int|null $time_blocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeOffRequest> $timeOffRequests
 * @property-read int|null $time_off_requests_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsScheduled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $site_id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property int $sort_order
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SiteUser> $memberships
 * @property-read int|null $memberships_count
 * @property-read \App\Models\Site|null $site
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkingHourPreset whereUpdatedAt($value)
 */
	class WorkingHourPreset extends \Eloquent {}
}

