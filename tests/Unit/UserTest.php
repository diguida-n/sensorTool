<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class UserTest extends TestCase
{
	use DatabaseMigrations;


	protected function setUp()
    {
    	parent::setUp();       
    }

    public function test_access_to_site()
    {
        $this->visit('/')
            ->see('Benvenuti sulla nostra Piattaforma!')
            ->dontSee('Accedi');
    }   

    public function test_login_as_admin()
    {
        $response = $this->post('admin/login', [
            'email' => $this->admin->email,
            'password' => 'admin'
        ]);
        $this->seeIsAuthenticatedAs($this->admin);
    }

    public function test_login_as_customer()
    {
        $response = $this->post('admin/login', [
            'email' => $this->customer->email,
            'password' => 'customer'
        ]);
        $this->seeIsAuthenticatedAs($this->customer);
    }

     public function test_login_as_guest()
    {   

        $response = $this->post('admin/login', [
            'email' => $this->guest->email,
            'password' => 'guest'
        ]);
        $this->seeIsAuthenticatedAs($this->guest);
    }

    public function test_logout_an_authenticated_user()
    {
        $user = $this->admin;
        $response = $this->actingAs($user)->post('/logout');
        $this->dontSeeIsAuthenticated();
    }
   

    public function test_register_as_customer()
    {

        $cryptedData = [];

        $cryptedData['role'] = 'Guest';
        $cryptedData['site_id'] = $this->site->id;
        $cryptedData['expiring_date'] = Carbon::now('Europe/Rome')->addDay()->toDateTimeString();
        $cryptedData['email'] = 'guest@sensorTool.com';
        $cryptedData = Crypt::encryptString(json_encode($cryptedData));

        $response = $this->post('/admin/register', [
            'name' => 'customer',
            'email' => 'customer@sensorTool.com',
            'password' => 'customer',
            'password_confirmation' => 'customer',
            'enterprise_id' => $this->enterprise->id,
            'role' => 'Customer',
            'expiring_date' => Carbon::now('Europe/Rome')->addDay()->toDateTimeString(),
            'cryptedData'  => $cryptedData
        ]);
        $this->seeIsAuthenticated();
    }
   
}