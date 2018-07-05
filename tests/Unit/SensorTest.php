<?php

namespace Tests\Unit;

use App\Models\Enterprise;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SensorTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
    	parent::setUp();
    }

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
