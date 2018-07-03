<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EnterpriseTest extends TestCase
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
   
   	/** @test */
    public function test_login_as_admin()
    {	
    	
        // $response = $this->call('POST','/admin/login', ['email'=>'admin@sensortool.com', 'password' =>'admin','_token'=> csrf_token()]);
         $this->visit('/admin/login')
             ->type('admin@sensortool.com', 'email')
             ->type('admin', 'password')
             ->press('Accedi')
             ->seePageIs('/admin/dashboard');
         // $this->assertResponseStatus(302, $response->status());
	
    	// $response->assertStatus(302); 
    }
}
