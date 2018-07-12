<?php

namespace Tests\Unit;

use App\Models\Enterprise;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EnterpriseTest extends TestCase
{
	use DatabaseMigrations;

	public function test_only_admin_access_admin_panel()
	{
		$this->actingAs($this->customer);
		$this->visit('/')
            ->click($this->customer->name)
            ->click('Area riservata')
            ->dontSee('Imprese');

        $this->visit('/admin/enterprise')
            ->seePageIs('/');

        $this->actingAs($this->guest);

        $this->visit('/')
            ->click($this->guest->name)
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

	public function test_go_to_add_new_enterprise ()
	{
		$this->actingAs($this->admin);
		$this->visit('/admin/enterprise')
            ->click('Aggiungi Impresa')
            ->seePageIs('/admin/enterprise/create');
	}

	public function test_add_new_enterprise()
	{
		$fields = [];
		$fields['businessName'] = 'IoT inc.';
		$fields['address'] = '{"name":"Via Edoardo Orabona","administrative":"Puglia","county":"Bari","city":"Bari","suburb":"Municipio 2","country":"Italia","countryCode":"it","type":"address","latlng":{"lat":41.1077,"lng":16.8798},"postcode":"70100","value":"Via Edoardo Orabona, Bari, Puglia, Italia"}';
		$fields['vatNumber'] = '12345674910';

		Enterprise::create($fields);

		$this->assertEquals(1, Enterprise::where($fields)->count());
	}

	public function test_edit_enterprise()
	{
		$this->enterprise->vatNumber = '12345674999';
		$this->enterprise->save();
		
		$this->assertEquals(1, Enterprise::where('vatNumber', $this->enterprise->vatNumber)->count());
	}

	public function test_delete_enterprise()
	{
		$this->enterprise->delete();

		$this->assertEquals(0, count(Enterprise::find($this->enterprise->id)));
	}

}
