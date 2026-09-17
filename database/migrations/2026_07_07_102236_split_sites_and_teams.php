<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /** @var list<string> */
    private array $teamForeignTables = [
        'working_hour_presets',
        'task_assignments',
        'time_off_requests',
        'lunch_bookings',
        'time_blocks',
        'tasks',
    ];

    public function up(): void
    {
        $this->dropTeamForeignKeys();

        $this->renameTable('teams', 'sites');
        $this->renameTable('team_user', 'legacy_memberships');

        $this->dropForeignKeysForColumn('legacy_memberships', 'team_id');
        $this->dropForeignKeysForColumn('legacy_memberships', 'user_id');
        $this->dropForeignKeysForColumn('legacy_memberships', 'working_hour_preset_id');

        $this->dropUniqueOnColumn('sites', 'slug');

        Schema::create('teams', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->constrained('sites')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('tasks_enabled')->default(true);
            $table->boolean('time_off_auto_approve')->default(false);
            $table->unsignedSmallInteger('minimum_available_staff')->nullable();
            $table->string('default_task')->default('none');
            $table->timestamps();

            $table->unique(['site_id', 'name']);
        });

        Schema::create('site_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_site_admin')->default(false);
            $table->timestamps();

            $table->unique(['site_id', 'user_id']);
        });

        /** @var array<string, string> $teamIdMap site_id => new team_id */
        $teamIdMap = [];

        foreach (DB::table('sites')->orderBy('created_at')->get() as $site) {
            $newTeamId            = (string) Str::uuid();
            $teamIdMap[$site->id] = $newTeamId;

            DB::table('teams')->insert([
                'id'                      => $newTeamId,
                'site_id'                 => $site->id,
                'name'                    => 'Everyone',
                'tasks_enabled'           => $site->tasks_enabled,
                'time_off_auto_approve'   => $site->time_off_auto_approve,
                'minimum_available_staff' => $site->minimum_available_staff,
                'default_task'            => $site->default_task,
                'created_at'              => $site->created_at,
                'updated_at'              => $site->updated_at,
            ]);
        }

        foreach ($this->teamForeignTables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($teamIdMap as $siteId => $newTeamId) {
                DB::table($table)->where('team_id', $siteId)->update(['team_id' => $newTeamId]);
            }
        }

        Schema::create('team_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('working_hour_preset_id')
                ->nullable()
                ->constrained('working_hour_presets')
                ->nullOnDelete();
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->boolean('time_off_requires_approval')->nullable();
            $table->boolean('is_scheduled')->default(false);
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
        });

        foreach (DB::table('legacy_memberships')->get() as $membership) {
            $newTeamId = $teamIdMap[$membership->team_id] ?? null;

            if ($newTeamId === null) {
                continue;
            }

            $isScheduled = (bool) DB::table('users')->where('id', $membership->user_id)->value('is_scheduled');

            DB::table('team_user')->insert([
                'team_id'                     => $newTeamId,
                'user_id'                     => $membership->user_id,
                'working_hour_preset_id'      => $membership->working_hour_preset_id,
                'onboarding_completed_at'     => $membership->onboarding_completed_at,
                'time_off_requires_approval'  => $membership->time_off_requires_approval,
                'is_scheduled'                => $isScheduled,
                'created_at'                  => $membership->created_at,
                'updated_at'                  => $membership->updated_at,
            ]);

            $isSiteAdmin = (bool) DB::table('users')->where('id', $membership->user_id)->value('is_admin');

            DB::table('site_user')->updateOrInsert(
                [
                    'site_id' => $membership->team_id,
                    'user_id' => $membership->user_id,
                ],
                [
                    'is_site_admin' => $isSiteAdmin,
                    'created_at'    => $membership->created_at,
                    'updated_at'    => $membership->updated_at,
                ],
            );
        }

        Schema::drop('legacy_memberships');

        Schema::table('sites', function (Blueprint $table): void {
            $table->unique('slug');
            $table->dropColumn([
                'tasks_enabled',
                'time_off_auto_approve',
                'minimum_available_staff',
                'default_task',
            ]);
        });

        $this->addTeamForeignKeys();
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table): void {
            $table->boolean('tasks_enabled')->default(true);
            $table->boolean('time_off_auto_approve')->default(false);
            $table->unsignedSmallInteger('minimum_available_staff')->nullable();
            $table->string('default_task')->default('none');
        });

        foreach (DB::table('teams')->get() as $team) {
            DB::table('sites')->where('id', $team->site_id)->update([
                'tasks_enabled'           => $team->tasks_enabled,
                'time_off_auto_approve'   => $team->time_off_auto_approve,
                'minimum_available_staff' => $team->minimum_available_staff,
                'default_task'            => $team->default_task,
            ]);
        }

        Schema::create('legacy_memberships', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('team_id');
            $table->foreignUuid('user_id');
            $table->foreignUuid('working_hour_preset_id')->nullable();
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->boolean('time_off_requires_approval')->nullable();
            $table->timestamps();
        });

        /** @var array<string, string> $siteIdMap team_id => site_id */
        $siteIdMap = DB::table('teams')->pluck('site_id', 'id')->all();

        foreach ($this->teamForeignTables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($siteIdMap as $teamId => $siteId) {
                DB::table($table)->where('team_id', $teamId)->update(['team_id' => $siteId]);
            }
        }

        foreach (DB::table('team_user')->get() as $membership) {
            $siteId = $siteIdMap[$membership->team_id] ?? null;

            if ($siteId === null) {
                continue;
            }

            DB::table('legacy_memberships')->insert([
                'team_id'                    => $siteId,
                'user_id'                    => $membership->user_id,
                'working_hour_preset_id'     => $membership->working_hour_preset_id,
                'onboarding_completed_at'    => $membership->onboarding_completed_at,
                'time_off_requires_approval' => $membership->time_off_requires_approval,
                'created_at'                 => $membership->created_at,
                'updated_at'                 => $membership->updated_at,
            ]);
        }

        Schema::drop('team_user');
        Schema::drop('site_user');
        Schema::drop('teams');
        $this->renameTable('legacy_memberships', 'team_user');
        $this->renameTable('sites', 'teams');
    }

    private function renameTable(string $from, string $to): void
    {
        Schema::rename($from, $to);

        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $this->renamePostgresIndexes($from, $to);
        $this->renamePostgresSequences($from, $to);
    }

    private function renamePostgresIndexes(string $from, string $to): void
    {
        $prefix = $from.'_';

        /** @var list<array{name: string}> $indexes */
        $indexes = Schema::getIndexes($to);

        foreach ($indexes as $index) {
            if (! str_starts_with($index['name'], $prefix)) {
                continue;
            }

            $newName = $to.mb_substr($index['name'], mb_strlen($from));

            Schema::table($to, function (Blueprint $table) use ($index, $newName): void {
                $table->renameIndex($index['name'], $newName);
            });
        }
    }

    private function renamePostgresSequences(string $from, string $to): void
    {
        $sequences = DB::select(
            <<<'SQL'
            select sequence.relname as name
            from pg_class as sequence
            inner join pg_depend as depend on depend.objid = sequence.oid and depend.deptype = 'a'
            inner join pg_class as table_class on table_class.oid = depend.refobjid
            inner join pg_namespace as namespace on namespace.oid = sequence.relnamespace
            where sequence.relkind = 'S'
              and namespace.nspname = current_schema()
              and table_class.relname = ?
            SQL,
            [$to],
        );

        $prefix = $from.'_';

        foreach ($sequences as $sequence) {
            if (! str_starts_with($sequence->name, $prefix)) {
                continue;
            }

            $newName = $to.mb_substr($sequence->name, mb_strlen($from));

            DB::statement(sprintf(
                'alter sequence %s rename to %s',
                $this->quotePostgresName($sequence->name),
                $this->quotePostgresName($newName),
            ));
        }
    }

    private function quotePostgresName(string $name): string
    {
        return '"'.str_replace('"', '""', $name).'"';
    }

    private function dropTeamForeignKeys(): void
    {
        $tables = ['team_user', ...$this->teamForeignTables];

        foreach ($tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            $this->dropForeignKeysForColumn($tableName, 'team_id');
        }
    }

    private function dropForeignKeysForColumn(string $tableName, string $column): void
    {
        /** @var list<array{name: string, columns: list<string>}> $foreignKeys */
        $foreignKeys = Schema::getForeignKeys($tableName);

        foreach ($foreignKeys as $foreignKey) {
            $columns = array_map(trim(...), $foreignKey['columns']);

            if (! in_array($column, $columns, true)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($foreignKey): void {
                $table->dropForeign($foreignKey['name']);
            });
        }
    }

    private function dropUniqueOnColumn(string $tableName, string $column): void
    {
        /** @var list<array{name: string, columns: list<string>, unique: bool, primary: bool}> $indexes */
        $indexes = Schema::getIndexes($tableName);

        foreach ($indexes as $index) {
            if (! $index['unique'] || $index['primary']) {
                continue;
            }

            $columns = array_map(trim(...), $index['columns']);

            if (! in_array($column, $columns, true)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($index): void {
                $table->dropUnique($index['name']);
            });
        }
    }

    private function addTeamForeignKeys(): void
    {
        foreach ($this->teamForeignTables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table): void {
                $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            });
        }
    }
};
