<?php

namespace Tests;

use App\Exceptions\Handler;
use App\Models\Enterprise;
use App\Models\SensorType;
use App\Models\Site;
use App\Models\SiteType;
use App\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    
    public $baseUrl = 'http://localhost';

    protected $site;
    protected $admin;
    protected $guest;
    protected $customer;
    protected $enterprise;
    protected $sensorType;
    protected $siteType;

    protected function setUp()
    {
    	parent::setUp();
    	$this->disableExceptionHandling();//https://gist.github.com/adamwathan/125847c7e3f16b88fa33a9f8b42333da


        $this->admin = User::create([
            'name' => "Admin",
            'email' => "admin@sensortool.com",
            'password' => bcrypt("admin"),
        ]);
        $this->admin->assignRole('Admin');

        $this->enterprise = Enterprise::create([
            'businessName' => "Enterprise",
            'address' => '{"name":"Via Giuseppe Re David","administrative":"Puglia","county":"Bari","city":"Bari","suburb":"Municipio 2","country":"Italia","countryCode":"it","type":"address","latlng":{"lat":41.1132,"lng":16.8762},"postcode":"70100","value":"Via Giuseppe Re David, Bari, Puglia, Italia"}',
            'vatNumber' => '12345678910',
        ]);

        $this->customer= User::create([
            'name' => "Customer",
            'email' => "customer@enterprise.com",
            'enterprise_id'=> $this->enterprise->id,
            'password' => bcrypt("customer")
        ]);
        $this->customer->assignRole('Customer');

        $this->siteType=SiteType::create(["name"=> "tipo sito 1"]);

        $this->site = Site::create([
            'name' => 'sito 1',
            'address' => '{
                "name":"Via Giuseppe Re David",
                "administrative":"Puglia",
                "county":"Bari",
                "city":"Bari",
                "suburb":"Municipio 2",
                "country":"Italia",
                "countryCode":"it",
                "type":"address",
                "latlng":{"lat":41.1132,"lng":16.8762},
                "postcode":"70100",
                "value":"Via Giuseppe Re David, Bari, Puglia, Italia"
            }',
            'map' => null,
            'description' => "description",
            'enterprise_id'=> $this->enterprise->id,
            'site_type_id' => $this->siteType->id
        ]);

        $this->guest = User::create([
            'name' => "Guest",
            'email' => "guest@enterprise.com",
            'site_id'=> $this->site->id,
            'password' => bcrypt("guest")
        ]);
        $this->guest->assignRole('Guest');

        $this->sensorType = SensorType::create(['name' => 'termometro semplice', 'description' => '']);
    }

    protected function signIn($user = null)	
    {
    	$user = $user ?:create('App\User');

    	$this->actingAs($user);

    	return $this;
    }


    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}
            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}
