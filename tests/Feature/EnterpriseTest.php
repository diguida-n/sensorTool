<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnterpriseTest extends TestCase
{
   public function test_admin_can_access_enterprise()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/enterprise')
			->seePageIs('/admin/enterprise');
    }

    public function test_customer_cant_access_enterprise()
    {
    	$this->actingAs($this->customer);

        $this->visit('/admin/enterprise')
			->seePageIs('/');
    }

    public function test_admin_can_add_enterprise()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/enterprise/create')
        	->type('Impresa di prova','businessName')
        	->type('12345678911','vatNumber')
        	->press('Salva e torna indietro')
			->seePageIs('/admin/enterprise');
    }

    public function test_businessName_is_required()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/enterprise/create')
        	->type('12345678911','vatNumber')
        	->press('Salva e torna indietro')
        	->see('Il campo Nome Impresa è richiesto.')
			->seePageIs('/admin/enterprise/create');
    }

    public function test_vatNumber_is_required()
    {
    	$this->actingAs($this->admin);

    	$this->withExceptionHandling();

        $this->visit('/admin/enterprise/create')
        	->type('Impresa di prova','businessName')
        	->press('Salva e torna indietro')
        	->see('Il campo Partita IVA è richiesto.')
			->seePageIs('/admin/enterprise/create');
    }

    public function test_admin_can_edit_enterprise()
    {
    	$this->actingAs($this->admin);

        $this->visit('/admin/enterprise/'.$this->enterprise->id.'/edit')
        	->see($this->enterprise->businessName)
        	->see($this->enterprise->vatNumber)
        	->press('Salva e torna indietro')
			->seePageIs('/admin/enterprise');
    }
}
