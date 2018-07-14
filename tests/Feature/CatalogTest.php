<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CatalogTest extends TestCase
{

    public function test_admin_can_access_catalog()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/sensorcatalog')
			->seePageIs('/admin/sensorcatalog');
    }

    public function test_customer_cant_access_catalog()
    {
    	$this->actingAs($this->customer);

        $this->visit('/admin/sensorcatalog')
			->seePageIs('/');
    }

    public function test_admin_can_add_sensor_catalog()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/sensorcatalog/create')
        	->type('Sensore di prova','name')
        	->type('descrizione del sensore di prova','description')
        	->type('0','min_detectable')
        	->type('100','max_detectable')
        	->select($this->sensorType->id, 'sensor_type_id')
        	->select($this->brand->id, 'brand_id')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/sensorcatalog');
    }


    public function test_name_of_sensor_catalog_is_required()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/sensorcatalog/create')
        	->type('descrizione del sensore di prova','description')
        	->type('0','min_detectable')
        	->type('100','max_detectable')
        	->select($this->sensorType->id, 'sensor_type_id')
        	->select($this->brand->id, 'brand_id')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/sensorcatalog/create')
			->see('Il campo Nome è richiesto.');
    }

    public function test_brand_is_required()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/sensorcatalog/create')
        	->type('Sensore di prova','name')
        	->type('descrizione del sensore di prova','description')
        	->type('0','min_detectable')
        	->type('100','max_detectable')
        	->select($this->sensorType->id, 'sensor_type_id')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/sensorcatalog/create')
			->see('Il campo Brand sensore è richiesto.');
    }

    public function test_sensorType_is_required()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/sensorcatalog/create')
        	->type('Sensore di prova','name')
        	->type('descrizione del sensore di prova','description')
        	->type('0','min_detectable')
        	->type('100','max_detectable')
        	->select($this->brand->id, 'brand_id')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/sensorcatalog/create')
			->see('Il campo Tipo sensore è richiesto.');
    }

    public function test_access_to_sensor_catalog_edit_page()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/sensorcatalog/'.$this->sensorCatalog->id.'/edit')
			->see($this->sensorCatalog->name)
			->see($this->sensorCatalog->description)
			->see($this->sensorCatalog->min_detectable)
			->see($this->sensorCatalog->max_detectable)
			->see($this->sensorCatalog->brand->name)
			->see($this->sensorCatalog->sensorType->name);
    }


    public function test_admin_can_access_brand()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/brand')
			->seePageIs('/admin/brand');
    }

    public function test_customer_cant_access_brand()
    {
    	$this->actingAs($this->customer);

        $this->visit('/admin/brand')
			->seePageIs('/');
    }

    public function test_admin_can_add_brand()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/brand/create')
        	->type('Marca di prova','name')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/brand');
    }

     public function test_name_of_brand_is_required()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/brand/create')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/brand/create')
			->see('Il campo Nome è richiesto.');
    }

    public function test_access_to_brand_edit_page()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/brand/'.$this->brand->id.'/edit')
			->see($this->brand->name);
    }

    public function test_admin_can_access_sensor_type()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/sensortype')
			->seePageIs('/admin/sensortype');
    }

    public function test_customer_cant_access_sensor_type()
    {
    	$this->actingAs($this->customer);

        $this->visit('/admin/sensortype')
			->seePageIs('/');
    }

    public function test_admin_can_add_sensor_typed()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/sensortype/create')
        	->type('Tipo di prova','name')
        	->type('Tipo di prova descrizione','description')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/sensortype');
    }

     public function test_name_of_sensor_type_is_required()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/sensortype/create')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/sensortype/create')
			->see('Il campo Nome è richiesto.');
    }

    public function test_access_to_sensor_type_edit_page()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/sensortype/'.$this->sensorType->id.'/edit')
			->see($this->sensorType->name)
			->see($this->sensorType->description);
    }
}
