<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    // public function setUp()
    // {
    //     parent::setUp();

    //     $this->signIn();
    // }
    
    /** test */
    public function guest_may_not_register()
    {
        $response = $this->get('/register');

        $response->assertStatus(403);
    }
}
