<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;

class BaseTestCase extends TestCase
{
    use refreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
//        $this->withoutExceptionHandling();
        \Artisan::call('passport:install');
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }
}
