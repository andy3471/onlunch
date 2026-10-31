<?php

declare(strict_types=1);

use App\Console\Commands\SchedulePrune;
use App\Console\Commands\TasksGenerate;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(TasksGenerate::class)
    ->daily();

Schedule::command(SchedulePrune::class)
    ->daily();
