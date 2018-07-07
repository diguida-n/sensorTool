<?php

namespace Tests;

use App\Models\Enterprise;
use App\Models\SiteType;
use App\User;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;


    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */

    public static function prepare()
    {
        static::startChromeDriver();

        // $admin = User::create([
        //     'name' => "Admin",
        //     'email' => "admin@sensortool.com",
        //     'password' => bcrypt("admin"),
        // ]);

        // $this->admin->assignRole('Admin');

        // $this->enterprise = Enterprise::create([
        //     'businessName' => "Enterprise",
        //     'address' => '{"name":"Via Giuseppe Re David","administrative":"Puglia","county":"Bari","city":"Bari","suburb":"Municipio 2","country":"Italia","countryCode":"it","type":"address","latlng":{"lat":41.1132,"lng":16.8762},"postcode":"70100","value":"Via Giuseppe Re David, Bari, Puglia, Italia"}',
        //     'vatNumber' => '12345678910',
        // ]);

        // $this->customer= User::create([
        //     'name' => "Customer",
        //     'email' => "customer@enterprise.com",
        //     'enterprise_id'=> $this->enterprise->id,
        //     'password' => bcrypt("customer")
        // ]);
        // $this->customer->assignRole('Customer');

        // $this->site = Site::create([
        //     'name' => 'sito 1',
        //     'address' => '{
        //         "name":"Via Giuseppe Re David",
        //         "administrative":"Puglia",
        //         "county":"Bari",
        //         "city":"Bari",
        //         "suburb":"Municipio 2",
        //         "country":"Italia",
        //         "countryCode":"it",
        //         "type":"address",
        //         "latlng":{"lat":41.1132,"lng":16.8762},
        //         "postcode":"70100",
        //         "value":"Via Giuseppe Re David, Bari, Puglia, Italia"
        //     }',
        //     'map' => null,
        //     'description' => "description",
        //     'enterprise_id'=> $this->enterprise->id,
        //     'site_type_id' => SiteType::create(["name"=> "tipo sito 1"])->id
        // ]);

        // $this->guest = User::create([
        //     'name' => "Guest",
        //     'email' => "guest@enterprise.com",
        //     'site_id'=> $this->site->id,
        //     'password' => bcrypt("guest")
        // ]);
        // $this->guest->assignRole('Guest');
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
}
