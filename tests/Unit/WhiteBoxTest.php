<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Enterprise;
use App\Models\SensorCatalog;
use App\Models\SensorType;
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
    /** Test Registrazione e Login **/
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
    
    /** FINE Test Registrazione e Login **/
    /** Test EnterpriseCrudController **/

    public function test_only_admin_access_admin_panel()
    {
        $this->actingAs($this->customer);
        $this->visit('/')
            ->click($this->customer->name)
            ->click('Area riservata')
            ->dontSee('Imprese');

        $this->visit('/admin/enterprise')
            ->seePageIs('/');

        $this->actingAs($this->guest);

        $this->visit('/')
            ->click($this->guest->name)
            ->click('Area riservata')
            ->dontSee('Imprese');

        $this->visit('/admin/enterprise')
            ->seePageIs('/');
    }
    public function test_go_to_admin_panel ()
    {
        $this->actingAs($this->admin);
        $this->visit('/')
            ->click($this->admin->name)
            ->click('Area riservata')
            ->see('Imprese');
    }

    public function test_go_to_enterprise_panel ()
    {
        $this->actingAs($this->admin);
        $this->visit('/admin')
            ->click('Imprese')
            ->seePageIs('/admin/enterprise')
            ->see('Aggiungi Impresa');
    }

    public function test_go_to_add_new_enterprise ()
    {
        $this->actingAs($this->admin);
        $this->visit('/admin/enterprise')
            ->click('Aggiungi Impresa')
            ->seePageIs('/admin/enterprise/create');
    }

    public function test_add_new_enterprise()
    {
        $fields = [];
        $fields['businessName'] = 'IoT inc.';
        $fields['address'] = '{"name":"Via Edoardo Orabona","administrative":"Puglia","county":"Bari","city":"Bari","suburb":"Municipio 2","country":"Italia","countryCode":"it","type":"address","latlng":{"lat":41.1077,"lng":16.8798},"postcode":"70100","value":"Via Edoardo Orabona, Bari, Puglia, Italia"}';
        $fields['vatNumber'] = '12345674910';

        Enterprise::create($fields);

        $this->assertEquals(1, Enterprise::where($fields)->count());
    }

    public function test_edit_enterprise()
    {
        $this->enterprise->vatNumber = '12345674999';
        $this->enterprise->save();
        
        $this->assertEquals(1, Enterprise::where('vatNumber', $this->enterprise->vatNumber)->count());
    }

    public function test_delete_enterprise()
    {
        $this->enterprise->delete();

        $this->assertEquals(0, count(Enterprise::find($this->enterprise->id)));
    }

    /** FINE Test EnterpriseCrudController **/
    /** Test SensorCatalogCrudController **/
    public function test_go_to_brand_panel ()
    {
        $this->actingAs($this->admin);
        $this->visit('/admin/brand')
            ->see('Aggiungi Brand');
    }

    public function test_add_new_brand()
    {
        $fields = [];
        $fields['name'] = 'Sigma';

        Brand::create($fields);

        $this->assertEquals(1, Brand::where($fields)->count());
    }

    public function test_edit_brand()
    {

        $brand = Brand::first();
        $brand->name = 'Sigma SPA';
        $brand->save();
        
        $this->assertEquals(1, Brand::where('name', $brand->name)->count());
    }

    public function test_delete_brand()
    {
        $brand = Brand::first();
        $brand->delete();

        $this->assertEquals(0, count(Brand::find($brand->id)));
    }

    /** FINE Test SensorCatalogCrudController **/
    /** Test BrandCrudController **/
    public function test_add_sensor_to_catalog()
    {

        $fields = [
            'name' => 'Sensore a caso',
            'description' => ' descrizione Sensore a caso',
            'min_detectable' => 0,
            'max_detectable' => 40,
            'sensor_type_id' => $this->sensorType->id,
            'brand_id' => $this->brand->id,
        ];
        SensorCatalog::create($fields);

        $this->assertEquals(1, SensorCatalog::where($fields)->count());
    }

    public function test_edit_sensor_to_catalog()
    {

        $sensor=SensorCatalog::find($this->sensorCatalog->id);
        $sensor->name = 'ora è modificato';
        $sensor->save();
        $this->assertEquals(1, SensorCatalog::where('name', 'ora è modificato')->count());
    }

    /** FINE Test BrandCrudController **/
    /** Test SensorTypeCrudController **/
    public function test_go_to_sensor_type_panel ()
    {
        $this->actingAs($this->admin);
        $this->visit('/admin/sensortype')
            ->see('Aggiungi Tipo di sensore');
    }

    public function test_add_new_sensor_type()
    {
        $fields = [];
        $fields['name'] = 'Termometro del sito';
        $fields['description'] = 'Serve per misurare la temperatura';

        $this->sensorType = SensorType::create($fields);

        $this->assertEquals(1, SensorType::where($fields)->count());
    }


    public function test_edit_sensor_type()
    {
        $this->sensorType->description = 'Serve a misurare la temperatura di un sito';
        $this->sensorType->save();
        
        $this->assertEquals(1, SensorType::where('description', $this->sensorType->description)->count());
    }

    public function test_delete_sensor_type()
    {
        $this->sensorType->delete();

        $this->assertEquals(0, count(SensorType::find($this->siteType->id)));
    }
    
    /** Test SensorCatalogCrudController,SensorCatalogCrudController,BrandCrudController, SensorCrudController **/

    public function test_only_admin_can_access_sensor_section()
    {

        $this->visit('/admin/sensorcatalog')
            ->seePageIs('/');

        $this->visit('admin/sensortype')
            ->seePageIs('/');

        $this->visit('admin/brand')
            ->seePageIs('/');

        $this->visit('admin/sensor')
            ->seePageIs('/');

        

        $this->actingAs($this->customer);

        $this->visit('admin/sensortype')
            ->seePageIs('/');

        $this->visit('admin/brand')
            ->seePageIs('/');

        $this->visit('admin/sensor')
            ->seePageIs('/');


        $this->actingAs($this->admin);
        $this->visit('/admin/sensorcatalog')
            ->seePageIs('/admin/sensorcatalog')
            ->see('Cataloghi')
            ->see('Tipi di Sensori')
            ->see('Brand sensori')
            ->see('Sensori');
    }
}