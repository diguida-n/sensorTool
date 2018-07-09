<?php

namespace Tests;

use App\Exceptions\Handler;
use App\Models\Enterprise;
use App\Models\Site;
use App\Models\SiteType;
use App\User;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Contracts\Debug\ExceptionHandler;

use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use DatabaseTransactions;
    
    public $baseUrl = 'http://localhost:8000';

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
            'address' => '',
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
            'address' => '',
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

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */

    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless'
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
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
