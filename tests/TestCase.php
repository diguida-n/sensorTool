<?php

namespace Tests;

use App\Exceptions\Handler;
use App\Models\Enterprise;
use App\Models\Site;
use App\Models\SiteType;
use App\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public $baseUrl = 'http://localhost';

    protected $site;
    protected $admin;
    protected $guest;
    protected $customer;
    protected $enterprise;

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
            'site_type_id' => SiteType::create(["name"=> "tipo sito 1"])->id
        ]);

        $this->guest = User::create([
            'name' => "Guest",
            'email' => "guest@enterprise.com",
            'site_id'=> $this->site->id,
            'password' => bcrypt("guest")
        ]);
        $this->guest->assignRole('Guest');
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
