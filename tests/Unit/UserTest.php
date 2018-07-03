<?php

namespace Tests\Feature;

use App\Models\Enterprise;
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

    private $enterprise;
	 protected function setUp()
    {
    	parent::setUp();

    	$u = User::create([
            'name' => "Admin",
            'email' => "admin@sensortool.com",
            'password' => bcrypt("admin"),
        ]);
        $u->assignRole('Admin');

        $this->enterprise = Enterprise::create([
            'businessName' => "Enterprise",
            'address' => '{"name":"Via Giuseppe Re David","administrative":"Puglia","county":"Bari","city":"Bari","suburb":"Municipio 2","country":"Italia","countryCode":"it","type":"address","latlng":{"lat":41.1132,"lng":16.8762},"postcode":"70100","value":"Via Giuseppe Re David, Bari, Puglia, Italia"}',
            'vatNumber' => '12345678910',
        ]);

        $customer= User::create([
            'name' => "Customer",
            'email' => "customer@enterprise.com",
            'enterprise_id'=> $this->enterprise->id,
            'password' => bcrypt("customer")
        ]);
        $customer->assignRole('Customer');

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
        $cryptedData['email'] = 'customer@sensrTool.com';
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
}