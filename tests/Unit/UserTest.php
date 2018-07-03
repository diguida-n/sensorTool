<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserTest extends TestCase
{
	use DatabaseMigrations;

	 protected function setUp()
    {
    	parent::setUp();

    	$u = User::create([
            'name' => "Admin",
            'email' => "admin@sensortool.com",
            'password' => bcrypt("admin"),
        ]);
	    $u->assignRole('Admin');

    }

    public function test_access_to_site()
    {
        $this->visit('/')
            ->see('Benvenuti sulla nostra Piattaforma!')
            ->dontSee('Accedi');
    }
   
    public function test_login_as_admin()
    {	
    	$this->visit('/admin/login')
             ->type('admin@sensortool.com', 'email')
             ->type('admin', 'password')
             ->press('Accedi')
             ->seePageIs('/');
    }
}