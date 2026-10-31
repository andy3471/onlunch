<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Actions\TaskAssignments\DeleteTaskAssignmentAction;
use App\Actions\TaskAssignments\StoreTaskAssignmentAction;
use App\Actions\TaskAssignments\UpdateTaskAssignmentAction;
use App\Actions\Tasks\ResolveTaskForAssignmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskAssignmentRequest;
use App\Http\Requests\UpdateTaskAssignmentRequest;
use App\Models\Site;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskAssignmentController extends Controller
{
    public function __construct(
        private readonly StoreTaskAssignmentAction $storeTaskAssignment,
        private readonly UpdateTaskAssignmentAction $updateTaskAssignment,
        private readonly DeleteTaskAssignmentAction $deleteTaskAssignment,
        private readonly ResolveTaskForAssignmentAction $resolveTask,
    ) {}

    public function store(StoreTaskAssignmentRequest $request, Site $site): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $assignee = $this->resolveAssignee($site, $actor, $request->validated('user_id'));
        $team     = $assignee->primaryTeamFor($site);

        abort_unless($team !== null, 403);

        $taskId = $this->resolveTask->execute(
            $team,
            $request->validated('task_name'),
            $actor->isSiteAdminFor($site),
        );

        $this->storeTaskAssignment->execute(
            $team,
            $assignee,
            $request->validated('date'),
            $request->validated('start_time'),
            $request->validated('end_time'),
            $taskId,
        );

        return redirect('/?date='.$request->validated('date'))
            ->with('message', 'Task added to the schedule.');
    }

    public function update(UpdateTaskAssignmentRequest $request, Site $site): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $record = TaskAssignment::query()
            ->whereIn('team_id', $site->teamIds())
            ->with(['timeBlock', 'team'])
            ->findOrFail((string) $request->route('taskAssignment'));

        $this->authorize('update', $record);

        $taskId = $this->resolveTask->execute(
            $record->team,
            $request->validated('task_name'),
            $actor->isSiteAdminFor($site),
        );

        $this->updateTaskAssignment->execute(
            $record->team,
            $actor,
            $record,
            $request->validated('start_time'),
            $request->validated('end_time'),
            $taskId,
        );

        $date = $record->timeBlock->date->toDateString();

        return redirect('/?date='.$date)->with('message', 'Schedule updated.');
    }

    public function destroy(Request $request, Site $site): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $record = TaskAssignment::query()
            ->whereIn('team_id', $site->teamIds())
            ->with('timeBlock')
            ->findOrFail((string) $request->route('taskAssignment'));

        $this->authorize('delete', $record);

        $date = $this->deleteTaskAssignment->execute($actor, $record);

        return redirect('/?date='.$date)->with('message', 'Task removed from the schedule.');
    }

    private function resolveAssignee(Site $site, User $actor, ?string $userId): User
    {
        if ($userId === null || $userId === $actor->id) {
            return $actor;
        }

        abort_unless($actor->isSiteAdminFor($site), 403);

        $assignee = $site->members()->whereKey($userId)->first();

        abort_if($assignee === null, 404);

        return $assignee;
    }
}
