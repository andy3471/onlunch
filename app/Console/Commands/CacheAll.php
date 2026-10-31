<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class CacheAll extends Command
{
    protected $signature = 'cache:all';

    protected $description = 'Warm application caches for production';

    public function handle(): int
    {
        $commands = [
            ['event:cache', 'Caching Events...'],
            ['route:cache', 'Caching Routes...'],
            ['config:cache', 'Caching Config...'],
            ['view:cache', 'Caching Views...'],
        ];

        foreach ($commands as [$command, $message]) {
            try {
                $this->info($message);
                Artisan::call($command);
            } catch (Throwable $e) {
                $this->error("Failed to cache {$command}: {$e->getMessage()}");
            }
        }

        return Command::SUCCESS;
    }
}
