<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamTaskRequest;
use App\Http\Requests\Admin\UpdateTeamTaskRequest;
use App\Models\Site;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(): Response
    {
        /** @var Site $site */
        $site        = resolve('currentSite');
        $defaultTeam = $site->defaultTeam();

        abort_unless($defaultTeam instanceof Team, 404);

        $tasks = $defaultTeam->tasks()
            ->orderBy('name')
            ->get()
            ->map(fn (Task $task): array => [
                'id'    => $task->id,
                'name'  => $task->name,
                'color' => $task->color,
            ]);

        return Inertia::render('Admin/Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Tasks/Form', [
            'task' => null,
        ]);
    }

    public function store(StoreTeamTaskRequest $request): RedirectResponse
    {
        /** @var Site $site */
        $site        = resolve('currentSite');
        $defaultTeam = $site->defaultTeam();

        abort_unless($defaultTeam instanceof Team, 404);

        $defaultTeam->tasks()->create($request->validated());

        return redirect('/manage/tasks')->with('message', 'Task created.');
    }

    public function edit(Request $request): Response
    {
        $task = $this->resolveTeamTask($request);

        return Inertia::render('Admin/Tasks/Form', [
            'task' => [
                'id'    => $task->id,
                'name'  => $task->name,
                'color' => $task->color,
            ],
        ]);
    }

    public function update(UpdateTeamTaskRequest $request): RedirectResponse
    {
        $task = $this->resolveTeamTask($request);
        $task->update($request->validated());

        return redirect('/manage/tasks')->with('message', 'Task updated.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $task = $this->resolveTeamTask($request);
        $task->delete();

        return redirect('/manage/tasks')->with('message', 'Task removed.');
    }

    protected function resolveTeamTask(Request $request): Task
    {
        /** @var Site $site */
        $site = resolve('currentSite');

        $task = Task::query()->findOrFail((string) $request->route('task'));

        abort_if(! in_array($task->team_id, $site->teamIds(), true), 404);

        return $task;
    }
}
