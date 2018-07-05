<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\Site;
use App\Models\SiteType;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
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
    	$this->visit('/admin/login')
             ->type('admin@sensortool.com', 'email')
             ->type('admin', 'password')
             ->press('Accedi')
             ->seePageIs('/');
    }

    public function test_register_as_customer()
    {
        $cryptedData = [];

        $cryptedData['role'] = 'Customer';
        $cryptedData['enterprise_id'] = $this->enterprise->id;
        $cryptedData['expiring_date'] = Carbon::now('Europe/Rome')->addDay()->toDateTimeString();
        $cryptedData['email'] = 'customer@sensorTool.com';
        $cryptedData = Crypt::encryptString(json_encode($cryptedData));
        $this->visit('/admin/register/'.$cryptedData)
            ->type('customer','name')
            ->type('customer','password')
            ->type('customer','password_confirmation')
            ->press('Registrati')
            ->seePageIs('/customer/dashboard');
    }

    public function test_login_as_customer()
    {   
        $this->visit('/admin/login')
             ->type('customer@enterprise.com', 'email')
             ->type('customer', 'password')
             ->press('Accedi')
             ->seePageIs('/customer/dashboard');
    }

    public function test_register_as_guest()
    {
        $cryptedData = [];

        $cryptedData['role'] = 'Guest';
        $cryptedData['site_id'] = $this->site->id;
        $cryptedData['expiring_date'] = Carbon::now('Europe/Rome')->addDay()->toDateTimeString();
        $cryptedData['email'] = 'guest@sensorTool.com';
        $cryptedData = Crypt::encryptString(json_encode($cryptedData));
        $this->visit('/admin/register/'.$cryptedData)
            ->type('customer','name')
            ->type('customer','password')
            ->type('customer','password_confirmation')
            ->press('Registrati')
            ->seePageIs('/customer/dashboard');
    }

    public function test_login_as_guest()
    {   
        $this->visit('/admin/login')
             ->type('guest@enterprise.com', 'email')
             ->type('guest', 'password')
             ->press('Accedi')
             ->seePageIs('/customer/dashboard');
    }
}