<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    public function test_customer_can_register_himself()
    {
		$cryptedData = [];

		$cryptedData['role'] = 'Customer';
		$cryptedData['enterprise_id'] = $this->enterprise->id;
		$cryptedData['expiring_date'] = Carbon::now('Europe/Rome')->addDay()->toDateTimeString();
		$cryptedData['email'] = 'customer@sensorTool.com';
		$cryptedData = Crypt::encryptString(json_encode($cryptedData));

		$this->visit('/admin/register/'.$cryptedData)
			->type('customer','name')
			->type('customer','password')
			->type('customer','password_confirmation')
			->press('Registrati')
			->seePageIs('/customer/dashboard');
	}

	public function test_customer_cant_register_himself1()
    {
    	$this->withExceptionHandling();

		$cryptedData = [];

		$cryptedData['role'] = 'Customer';
		$cryptedData['enterprise_id'] = $this->enterprise->id;
		$cryptedData['expiring_date'] = Carbon::now('Europe/Rome')->addDay()->toDateTimeString();
		$cryptedData['email'] = 'customersensorTool.com';
		$cryptedData = Crypt::encryptString(json_encode($cryptedData));

		// print_r($cryptedData);
		$this->visit('/admin/register/'.$cryptedData)
			->type('customer','name')
			->type('custodmer','password')
			->type('customer','password_confirmation')
			->press('Registrati')
			->seePageIs('/admin/register/'.$cryptedData)
			->see('email non è valido')
			->see('Il campo di conferma per password non coincide');

		$cryptedData = [];
		$cryptedData['role'] = 'Customer';
		$cryptedData['enterprise_id'] = $this->enterprise->id;
		$cryptedData['expiring_date'] = Carbon::now('Europe/Rome')->addDay()->toDateTimeString();
		$cryptedData['email'] = 'customer@sensorTool.com';
		$cryptedData = Crypt::encryptString(json_encode($cryptedData));

		$this->visit('/admin/register/'.$cryptedData)
			->type('customer','name')
			->type('c1','password')
			->type('customer','password_confirmation')
			->press('Registrati')
			->seePageIs('/admin/register/'.$cryptedData)
			->see('password deve contenere almeno 6 caratteri');

	}

	public function test_customer_can_login_himself()
    {
		$this->visit('/admin/login')
			->type($this->customer->email,'email')
			->type('customer','password')
			->press('Accedi')
			->seePageIs('/customer/dashboard');
	}

	public function test_customer_cant_login_himself()
    {
    	$this->withExceptionHandling();

		$this->visit('/admin/login')
			->type('customersensorTool.com','email')
			->type('customer','password')
			->press('Accedi')
			->seePageIs('/admin/login');

		$this->visit('/admin/login')
			->type($this->customer->email,'email')
			->type('customemr','password')
			->press('Accedi')
			->see('Credenziali non corrispondenti');
	}
}
