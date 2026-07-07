<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Models\WorkingHourPreset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $site = Site::factory()->create([
            'name'                   => 'Demo Site',
            'slug'                   => 'demo',
            'register_enabled'       => true,
            'reset_password_enabled' => false,
        ]);

        $team = Team::factory()->for($site)->create([
            'name'                    => 'Everyone',
            'tasks_enabled'           => true,
            'time_off_auto_approve'   => false,
            'minimum_available_staff' => 2,
            'default_task'            => 'In Office',
        ]);

        $nineToFive = WorkingHourPreset::create([
            'site_id'    => $site->id,
            'name'       => 'Standard',
            'start_time' => '09:00:00',
            'end_time'   => '17:00:00',
            'sort_order' => 0,
        ]);

        WorkingHourPreset::create([
            'site_id'    => $site->id,
            'name'       => 'Late shift',
            'start_time' => '10:00:00',
            'end_time'   => '18:00:00',
            'sort_order' => 1,
        ]);

        $admin = User::factory()->admin()->create([
            'email' => 'admin@admin.com',
        ]);

        $site->members()->attach($admin->id, [
            'is_site_admin'           => true,
            'working_hour_preset_id'  => $nineToFive->id,
            'onboarding_completed_at' => Date::now(),
        ]);
        $team->members()->attach($admin->id, ['is_scheduled' => true]);

        $users = User::factory(10)->create();
        foreach ($users as $user) {
            $site->members()->attach($user->id, [
                'is_site_admin'           => false,
                'working_hour_preset_id'  => $nineToFive->id,
                'onboarding_completed_at' => Date::now(),
            ]);
            $team->members()->attach($user->id, ['is_scheduled' => true]);
        }

        Task::create(['name' => 'In Office', 'color' => '#6366f1', 'team_id' => $team->id]);
        Task::create(['name' => 'Working From Home', 'color' => '#8b5cf6', 'team_id' => $team->id]);
        Task::create(['name' => 'Deep Work', 'color' => '#0ea5e9', 'team_id' => $team->id]);
    }
}
