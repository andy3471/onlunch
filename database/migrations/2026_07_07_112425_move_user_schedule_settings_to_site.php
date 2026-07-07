<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('working_hour_presets', function (Blueprint $table): void {
            $table->foreignUuid('site_id')->nullable()->after('id')->constrained('sites')->cascadeOnDelete();
        });

        foreach (DB::table('working_hour_presets')->get() as $preset) {
            $siteId = DB::table('teams')->where('id', $preset->team_id)->value('site_id');

            if ($siteId !== null) {
                DB::table('working_hour_presets')->where('id', $preset->id)->update(['site_id' => $siteId]);
            }
        }

        Schema::table('site_user', function (Blueprint $table): void {
            $table->foreignUuid('working_hour_preset_id')
                ->nullable()
                ->after('is_site_admin')
                ->constrained('working_hour_presets')
                ->nullOnDelete();
            $table->timestamp('onboarding_completed_at')->nullable()->after('working_hour_preset_id');
            $table->boolean('time_off_requires_approval')->nullable()->after('onboarding_completed_at');
        });

        foreach (DB::table('site_user')->get() as $siteMembership) {
            $teamMembership = DB::table('team_user')
                ->join('teams', 'teams.id', '=', 'team_user.team_id')
                ->where('teams.site_id', $siteMembership->site_id)
                ->where('team_user.user_id', $siteMembership->user_id)
                ->orderBy('teams.created_at')
                ->select('team_user.*')
                ->first();

            if ($teamMembership === null) {
                continue;
            }

            DB::table('site_user')->where('id', $siteMembership->id)->update([
                'working_hour_preset_id'     => $teamMembership->working_hour_preset_id,
                'onboarding_completed_at'    => $teamMembership->onboarding_completed_at,
                'time_off_requires_approval' => $teamMembership->time_off_requires_approval,
            ]);
        }

        Schema::table('team_user', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('working_hour_preset_id');
            $table->dropColumn(['onboarding_completed_at', 'time_off_requires_approval']);
        });

        Schema::table('working_hour_presets', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('team_id');
        });
    }

    public function down(): void
    {
        Schema::table('working_hour_presets', function (Blueprint $table): void {
            $table->foreignUuid('team_id')->nullable()->after('id')->constrained('teams')->cascadeOnDelete();
        });

        foreach (DB::table('working_hour_presets')->get() as $preset) {
            $teamId = DB::table('teams')
                ->where('site_id', $preset->site_id)
                ->orderBy('created_at')
                ->value('id');

            if ($teamId !== null) {
                DB::table('working_hour_presets')->where('id', $preset->id)->update(['team_id' => $teamId]);
            }
        }

        Schema::table('team_user', function (Blueprint $table): void {
            $table->foreignUuid('working_hour_preset_id')
                ->nullable()
                ->constrained('working_hour_presets')
                ->nullOnDelete();
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->boolean('time_off_requires_approval')->nullable();
        });

        foreach (DB::table('site_user')->get() as $siteMembership) {
            $teamId = DB::table('teams')
                ->where('site_id', $siteMembership->site_id)
                ->orderBy('created_at')
                ->value('id');

            if ($teamId === null) {
                continue;
            }

            DB::table('team_user')->updateOrInsert(
                ['team_id' => $teamId, 'user_id' => $siteMembership->user_id],
                [
                    'working_hour_preset_id'     => $siteMembership->working_hour_preset_id,
                    'onboarding_completed_at'    => $siteMembership->onboarding_completed_at,
                    'time_off_requires_approval' => $siteMembership->time_off_requires_approval,
                    'is_scheduled'               => false,
                    'created_at'                 => now(),
                    'updated_at'                 => now(),
                ],
            );
        }

        Schema::table('site_user', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('working_hour_preset_id');
            $table->dropColumn(['onboarding_completed_at', 'time_off_requires_approval']);
        });

        Schema::table('working_hour_presets', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('site_id');
        });
    }
};
