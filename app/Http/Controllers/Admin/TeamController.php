<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamRequest;
use App\Http\Requests\Admin\UpdateTeamRequest;
use App\Models\Site;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    public function index(): Response
    {
        /** @var Site $site */
        $site = resolve('currentSite');

        $teams = $site->teams()
            ->orderBy('created_at')
            ->get(['id', 'name', 'minimum_available_staff', 'tasks_enabled'])
            ->map(fn ($team): array => [
                'id'                    => $team->id,
                'name'                  => $team->name,
                'minimumAvailableStaff' => $team->minimum_available_staff,
                'tasksEnabled'          => $team->tasks_enabled,
            ]);

        return Inertia::render('Admin/Teams/Index', [
            'teams' => $teams,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Teams/Form', [
            'team' => null,
        ]);
    }

    public function store(StoreTeamRequest $request): RedirectResponse
    {
        /** @var Site $site */
        $site      = resolve('currentSite');
        $validated = $request->validated();

        $site->teams()->create([
            'name'                    => $validated['name'],
            'minimum_available_staff' => $validated['minimum_available_staff'] ?? null,
        ]);

        return redirect('/manage/teams')->with('message', 'Team created.');
    }

    public function edit(Request $request): Response
    {
        $team = $this->resolveTeam($request);

        return Inertia::render('Admin/Teams/Form', [
            'team' => [
                'id'                    => $team->id,
                'name'                  => $team->name,
                'minimumAvailableStaff' => $team->minimum_available_staff,
            ],
        ]);
    }

    public function update(UpdateTeamRequest $request): RedirectResponse
    {
        $team      = $this->resolveTeam($request);
        $validated = $request->validated();

        $team->update([
            'name'                    => $validated['name'],
            'minimum_available_staff' => $validated['minimum_available_staff'] ?? null,
        ]);

        return redirect('/manage/teams')->with('message', 'Team updated.');
    }

    protected function resolveTeam(Request $request): Team
    {
        /** @var Site $site */
        $site = resolve('currentSite');

        $team = Team::query()->findOrFail((string) $request->route('team'));

        abort_if($team->site_id !== $site->id, 404);

        return $team;
    }
}
