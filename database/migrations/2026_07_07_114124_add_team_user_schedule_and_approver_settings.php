<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_user', function (Blueprint $table): void {
            $table->string('schedule_visibility')->default('everyone')->after('is_scheduled');
            $table->boolean('is_time_off_approver')->default(false)->after('schedule_visibility');
        });
    }

    public function down(): void
    {
        Schema::table('team_user', function (Blueprint $table): void {
            $table->dropColumn(['schedule_visibility', 'is_time_off_approver']);
        });
    }
};
