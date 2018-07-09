<?php

namespace Tests\Browser;

use App\Models\Enterprise;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Crypt;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
{
    

    /*@test

    */
    public function test_access_to_site()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Benvenuti sulla nostra Piattaforma!')
                ->assertDontSee('Accedi');
        });
    }
    /*@test

    */
    public function test_login_as_admin()
    {   
        $admin = User::create([
            'name' => "Admin",
            'email' => "admin@sensortool.com",
            'password' => bcrypt("admin"),
        ]);

        $admin->assignRole('Admin');

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/admin/login')
                ->type('email', $admin->email)
                ->type('password', 'admin')
                ->press('Accedi')
                ->assertPathIs('/');
        });
    }

    /*@test

    */
    public function test_register_as_customer()
    {

        $enterprise = Enterprise::create([
            'businessName' => "Enterprise",
            'address' => '{"name":"Via Re David","administrative":"Puglia","county":"Bari","city":"Bari","suburb":"","country":"Italia","countryCode":"it","type":"address","latlng":{"lat":41.1132,"lng":16.8762},"postcode":"70100","value":"Via Re David, Bari, Puglia, Italia"}',
            'vatNumber' => '12345678910',
        ]);
        $cryptedData = [];

        $cryptedData['role'] = 'Customer';
        $cryptedData['enterprise_id'] = $enterprise->id;
        $cryptedData['expiring_date'] = Carbon::now('Europe/Rome')->addDay()->toDateTimeString();
        $cryptedData['email'] = 'customer@sensorTool.com';
        $cryptedData = Crypt::encryptString(json_encode($cryptedData));

        $this->browse(function (Browser $browser) use ($cryptedData) {
            $browser->visit('/admin/register/'.$cryptedData)
                ->type('name','customer')
                ->type('password', 'customer')
                ->type('password_confirmation', 'customer')
                ->press('Registrati')
                ->assertPathIs('/customer/dashboard');
        });
    }

}
