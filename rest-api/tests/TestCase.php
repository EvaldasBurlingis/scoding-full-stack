<?php

namespace Tests;

use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // $this->withoutExceptionHandling();

    public function setUp(): void {
        parent::setUp();
        
        $this->seed(PermissionsSeeder::class);
    }
}
