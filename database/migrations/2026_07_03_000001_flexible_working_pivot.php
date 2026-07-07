<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('lunch_slot_user');
        Schema::dropIfExists('lunch_slots');
        Schema::dropIfExists('role_user');

        Schema::table('teams', function (Blueprint $table) {
            $table->renameColumn('roles_enabled', 'tasks_enabled');
            $table->renameColumn('default_role', 'default_task');
            $table->boolean('time_off_auto_approve')->default(false)->after('tasks_enabled');
            $table->unsignedSmallInteger('minimum_available_staff')->nullable()->after('time_off_auto_approve');
            $table->dropColumn(['lunch_slot_calculated', 'lunch_slot_calculated_ratio']);
        });

        Schema::rename('roles', 'tasks');

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('is_available');
            $table->string('color')->nullable()->after('name');
        });

        Schema::create('working_hour_presets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['team_id', 'sort_order']);
        });

        Schema::table('team_user', function (Blueprint $table) {
            $table->foreignUuid('working_hour_preset_id')
                ->nullable()
                ->after('user_id')
                ->constrained('working_hour_presets')
                ->nullOnDelete();
            $table->timestamp('onboarding_completed_at')->nullable()->after('working_hour_preset_id');
            $table->boolean('time_off_requires_approval')->nullable()->after('onboarding_completed_at');
        });

        Schema::create('task_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->timestamps();

            $table->index(['team_id', 'user_id']);
        });

        Schema::create('time_off_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'status']);
        });

        Schema::create('lunch_bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index(['team_id', 'user_id']);
        });

        Schema::create('time_blocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->uuidMorphs('blockable');
            $table->timestamps();

            $table->index(['team_id', 'date']);
            $table->index(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_blocks');
        Schema::dropIfExists('lunch_bookings');
        Schema::dropIfExists('time_off_requests');
        Schema::dropIfExists('task_assignments');

        Schema::table('team_user', function (Blueprint $table) {
            $table->dropConstrainedForeignId('working_hour_preset_id');
            $table->dropColumn(['onboarding_completed_at', 'time_off_requires_approval']);
        });

        Schema::dropIfExists('working_hour_presets');

        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('is_available')->default(true);
            $table->dropColumn('color');
        });

        Schema::rename('tasks', 'roles');

        Schema::table('teams', function (Blueprint $table) {
            $table->renameColumn('tasks_enabled', 'roles_enabled');
            $table->renameColumn('default_task', 'default_role');
            $table->dropColumn(['time_off_auto_approve', 'minimum_available_staff']);
            $table->boolean('lunch_slot_calculated')->default(false);
            $table->decimal('lunch_slot_calculated_ratio', 3, 2)->default(0.33);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('role_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->timestamps();
            $table->index(['user_id', 'date']);
            $table->index(['date']);
        });

        Schema::create('lunch_slots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->constrained()->cascadeOnDelete();
            $table->time('time');
            $table->integer('available')->default(3);
            $table->timestamps();
        });

        Schema::create('lunch_slot_user', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('lunch_slot_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->timestamps();
            $table->index(['lunch_slot_id', 'date']);
            $table->index(['user_id', 'date']);
        });
    }
};
