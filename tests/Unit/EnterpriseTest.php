<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EnterpriseTest extends TestCase
{

	use DatabaseMigrations;
	public function setUp($value='')
	{
		parent::setUp();
	}


	public function test_only_admin_access_admin_panel()
	{
		$this->actingAs($this->customer);
		$this->visit('/')
            ->click($this->customer->name)
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

	public function test_add_new_enterprise ()
	{
		$this->actingAs($this->admin);
		$this->visit('/admin/enterprise')
            ->click('Aggiungi Impresa')
            ->seePageIs('/admin/enterprise/create');
	}
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
