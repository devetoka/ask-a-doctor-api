<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;

class BaseTestCase extends TestCase
{
    use refreshDatabase;
    protected $user;
    protected function setUp(): void
    {
        parent::setUp();
//        $this->withoutExceptionHandling();
        \Artisan::call('passport:install');
        $this->withoutMiddleware(
            ThrottleRequests::class
        );

        $this->user = factory(User::class)->create();

    }

    public function login()
    {
        $this->actingAs($this->user, 'api');
    }
}
