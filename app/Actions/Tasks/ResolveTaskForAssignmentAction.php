<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Actions\Concerns\AsAction;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Support\Str;

class ResolveTaskForAssignmentAction
{
    use AsAction;

    public function execute(Team $team, string $taskName): ?string
    {
        $normalized = mb_trim($taskName);

        if ($normalized === '' || Str::lower($normalized) === 'away') {
            return null;
        }

        $existing = $team->tasks()
            ->whereRaw('LOWER(name) = ?', [Str::lower($normalized)])
            ->first();

        if ($existing !== null) {
            return $existing->id;
        }

        return Task::query()->create([
            'team_id' => $team->id,
            'name'    => $normalized,
            'color'   => null,
        ])->id;
    }
}
