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
}
