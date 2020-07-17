<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    use refreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
//        $this->withoutExceptionHandling();
        \Artisan::call('passport:install');
    }
}
