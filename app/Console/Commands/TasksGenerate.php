<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Tasks\GenerateDefaultTaskAssignmentsAction;
use Illuminate\Console\Command;

class TasksGenerate extends Command
{
    protected $signature = 'tasks:generate';

    protected $description = 'Generate default task assignments for scheduled users';

    public function handle(GenerateDefaultTaskAssignmentsAction $generate): int
    {
        $generate->execute(report: function (string $message): void {
            $this->line($message);
        });

        return self::SUCCESS;
    }
}
