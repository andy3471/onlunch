<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Users\StoreTeamMemberAction;
use App\Actions\Users\UpdateTeamMemberAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamMemberRequest;
use App\Http\Requests\Admin\UpdateTeamMemberRequest;
use App\Models\Site;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use App\Models\WorkingHourPreset;
use App\Support\TimeInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly StoreTeamMemberAction $storeTeamMember,
        private readonly UpdateTeamMemberAction $updateTeamMember,
    ) {}

    public function index(): Response
    {
        /** @var Site $site */
        $site = resolve('currentSite');

        $users = $site->members()
            ->with(['teams' => fn ($query) => $query->where('site_id', $site->id)])
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use ($site): array {
                $teams = $user->teamsForSite($site);

                return [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'isAdmin'     => $user->isSiteAdminFor($site),
                    'teamNames'   => $teams->pluck('name')->all(),
                    'isScheduled' => $teams->contains(
                        fn (Team $team): bool => (bool) $user->membershipFor($team)?->is_scheduled,
                    ),
                ];
            });

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Form', $this->formProps());
    }

    public function store(StoreTeamMemberRequest $request): RedirectResponse
    {
        /** @var Site $site */
        $site = resolve('currentSite');

        $this->storeTeamMember->execute($site, $request->validated());

        return redirect('/manage/users')->with('message', 'User created.');
    }

    public function edit(Request $request): Response
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $user = $this->resolveUser($request, $site);

        return Inertia::render('Admin/Users/Form', [
            ...$this->formProps($user),
            'user' => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'isAdmin' => $user->isSiteAdminFor($site),
            ],
        ]);
    }

    public function update(UpdateTeamMemberRequest $request): RedirectResponse
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $user = $this->resolveUser($request, $site);

        $this->updateTeamMember->execute($site, $user, $request->validated());

        return redirect('/manage/users')->with('message', 'User updated.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $user = $this->resolveUser($request, $site);

        $user->delete();

        return redirect('/manage/users')->with('message', 'User removed.');
    }

    /** @return array<string, mixed> */
    protected function formProps(?User $user = null): array
    {
        /** @var Site $site */
        $site = resolve('currentSite');

        $presets = $site->workingHourPresets->map(fn (WorkingHourPreset $preset): array => [
            'id'        => $preset->id,
            'name'      => $preset->name,
            'startTime' => TimeInput::normalize((string) $preset->getRawOriginal('start_time')),
            'endTime'   => TimeInput::normalize((string) $preset->getRawOriginal('end_time')),
            'label'     => $preset->label(),
        ]);

        $siteMembership = $user instanceof User ? $user->siteMembershipFor($site) : null;

        $timeOffApproval = match (true) {
            $siteMembership?->time_off_requires_approval === true  => 'required',
            $siteMembership?->time_off_requires_approval === false => 'auto',
            default                                                => 'site_default',
        };

        return [
            'presets'             => $presets,
            'teams'               => $site->teams()->orderBy('created_at')->get(['id', 'name']),
            'defaultTeamId'       => $site->defaultTeam()?->id,
            'workingHourPresetId' => $siteMembership?->working_hour_preset_id,
            'timeOffApproval'     => $user instanceof User ? $timeOffApproval : 'site_default',
            'teamMemberships'     => $user instanceof User
                ? $this->teamMembershipsForUser($site, $user)
                : $this->defaultTeamMemberships($site),
            'user'                => null,
        ];
    }

    /** @return list<array{teamId: string, isMember: bool, isScheduled: bool, scheduleVisibility: string, isTimeOffApprover: bool}> */
    protected function teamMembershipsForUser(Site $site, User $user): array
    {
        return $site->teams()
            ->orderBy('created_at')
            ->get()
            ->map(function (Team $team) use ($user): array {
                $membership = $user->membershipFor($team);

                return [
                    'teamId'              => $team->id,
                    'isMember'            => $membership instanceof TeamUser,
                    'isScheduled'         => $membership?->is_scheduled                ?? false,
                    'scheduleVisibility'  => $membership?->schedule_visibility?->value ?? 'everyone',
                    'isTimeOffApprover'   => $membership?->is_time_off_approver        ?? false,
                ];
            })
            ->all();
    }

    /** @return list<array{teamId: string, isMember: bool, isScheduled: bool, scheduleVisibility: string, isTimeOffApprover: bool}> */
    protected function defaultTeamMemberships(Site $site): array
    {
        $defaultTeam = $site->defaultTeam();

        return $site->teams()
            ->orderBy('created_at')
            ->get()
            ->map(function (Team $team) use ($defaultTeam): array {
                $isDefault = $defaultTeam instanceof Team && $team->id === $defaultTeam->id;

                return [
                    'teamId'              => $team->id,
                    'isMember'            => $isDefault,
                    'isScheduled'         => $isDefault,
                    'scheduleVisibility'  => 'everyone',
                    'isTimeOffApprover'   => false,
                ];
            })
            ->all();
    }

    protected function resolveSiteMember(Site $site, User $user): void
    {
        abort_unless($site->members()->whereKey($user->id)->exists(), 404);
    }

    protected function resolveUser(Request $request, Site $site): User
    {
        $user = User::query()->findOrFail((string) $request->route('user'));

        $this->resolveSiteMember($site, $user);

        return $user;
    }
}
