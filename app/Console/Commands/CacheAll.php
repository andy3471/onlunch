<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class CacheAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** Execute the console command. */
    public function handle(): int
    {
        $commands = [
            ['icons:cache', 'Caching Icons...'],
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
