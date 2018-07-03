<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Benvenuti sulla nostra Piattaforma!')
             ->dontSee('Accedi');
    }
}
