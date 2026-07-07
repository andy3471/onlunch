<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Concerns\CreatesSiteWithTeam;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use CreatesSiteWithTeam;

    protected function setUp(): void
    {
        parent::setUp();

        config(['debugbar.enabled' => false]);

        $this->withoutMiddleware(\BeyondCode\QueryDetector\QueryDetectorMiddleware::class);
    }
}
