<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiteTest extends TestCase
{
    public function test_admin_can_access_site()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/site')
			->seePageIs('/admin/site');
    }

    public function test_customer_cant_access_site()
    {
    	$this->actingAs($this->customer);

        $this->visit('/admin/site')
			->seePageIs('/');
    }

    public function test_admin_can_add_site()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/site/create')
        	->select($this->enterprise->id, 'enterprise_id')
        	->type('Sensore di prova','name')
        	->type('descrizione del sensore di prova','description')
        	->attach('/img/contact-section.jpg', 'map')
        	->select($this->siteType->id, 'site_type_id')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/site');
    }

    public function test_admin_can_edit_site()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/site/'.$this->site->id.'/edit')
        	->see($this->site->enterprise->businessName)
        	->see($this->site->name)
        	->see($this->site->description)
        	->see($this->site->siteType->name)
        	->press('Salva e torna indietro')
			->seePageIs('/admin/site');
    }

    public function test_admin_can_access_site_type()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/sitetype')
			->seePageIs('/admin/sitetype');
    }

    public function test_customer_cant_access_site_type()
    {
    	$this->actingAs($this->customer);

        $this->visit('/admin/sitetype')
			->seePageIs('/');
    }

    public function test_admin_can_add_site_type()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/sitetype/create')
        	->type('tipo di sito di prova','name')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/sitetype');
    }

    public function test_admin_can_edit_site_type()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/sitetype/'.$this->siteType->id.'/edit')
        	->see($this->siteType->name)
        	->press('Salva e torna indietro')
			->seePageIs('/admin/sitetype');
    }


    public function test_admin_can_add_sensor_to_site()
    {
        $this->actingAs($this->admin);

        $this->visit('/admin/sensor/create')
        ->type('0','min_attended')
            ->type('80','max_attended')
            ->type('41.9102415','latitude')
            ->type('12.3959123','longitude')
            ->select($this->site->id,'site_id')
            ->select($this->sensorCatalog->id,'sensor_catalog_id')
            ->press('Salva e torna indietro')
            ->seePageIs('/admin/sensor');
    }

    public function test_admin_cant_add_sensor_to_site_not_selected_site()
    {
        $this->withExceptionHandling();


        $this->actingAs($this->admin);

        $this->visit('/admin/sensor/create')
        ->type('0','min_attended')
            ->type('80','max_attended')
            ->type('41.9102415','latitude')
            ->type('12.3959123','longitude')
            // ->select(null,'site_id')
            ->select($this->sensorCatalog->id,'sensor_catalog_id')
            ->press('Salva e torna indietro')
            ->seePageIs('/admin/sensor/create')
             ->see('Id Sito selezionato non è valido');
    }

    public function test_admin_cant_add_sensor_to_site_not_selected_sensor()
    {
        $this->withExceptionHandling();

        $this->actingAs($this->admin);

        $this->visit('/admin/sensor/create')
        ->type('0','min_attended')
            ->type('80','max_attended')
            ->type('41.9102415','latitude')
            ->type('12.3959123','longitude')
            ->select($this->site->id,'site_id')
            ->press('Salva e torna indietro')
            ->seePageIs('/admin/sensor/create')
            ->see('Id Sensore selezionato non è valido');
    }
}
