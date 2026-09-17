<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SplitSitesAndTeamsMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrated_schema_has_sites_and_teams(): void
    {
        $this->assertTrue(Schema::hasTable('sites'));
        $this->assertTrue(Schema::hasTable('teams'));
        $this->assertTrue(Schema::hasTable('site_user'));
        $this->assertTrue(Schema::hasTable('team_user'));
        $this->assertTrue(Schema::hasColumn('teams', 'site_id'));
        $this->assertFalse(Schema::hasTable('legacy_memberships'));
    }
}
