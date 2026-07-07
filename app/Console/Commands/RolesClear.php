<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TimeBlock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class RolesClear extends Command
{
    protected $signature = 'roles:clear';

    protected $description = 'Clear schedule entries more than 3 weeks old';

    public function handle(): void
    {
        $cutoff = Date::now()->subWeeks(3)->toDateString();

        $deleted = 0;

        TimeBlock::query()
            ->where('date', '<', $cutoff)
            ->with('blockable')
            ->each(function (TimeBlock $block) use (&$deleted): void {
                $block->blockable?->delete();
                $deleted++;
            });

        $this->line("Deleted {$deleted} entries older than {$cutoff}");
    }
}
