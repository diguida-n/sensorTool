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
	private $admin;
	public function setUp($value='')
	{
		parent::setUp();
		$this->admin = User::create([
            'name' => "Admin",
            'email' => "admin@sensortool.com",
            'password' => bcrypt("admin"),
        ]);
        $this->admin->assignRole('Admin');

        
	}


	public function test_only_admin_access_admin_panel()
	{
		$customer = User::create([
            'name' => "customer",
            'email' => "customer@sensortool.com",
            'enterprise_id'=> 1,
            'password' => bcrypt("customer"),
        ]);
		$customer->assignRole('Customer');
		$this->actingAs($customer);
		$this->visit('/')
            ->click($customer->name)
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
