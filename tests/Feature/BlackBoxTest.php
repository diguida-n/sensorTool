<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use Carbon\Carbon;

class BlackBoxTest extends TestCase
{

    /** Test Registrazione e Login **/
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
    /** FINE Test Registrazione e Login **/
    /** Test EnterpriseCrudController **/
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

    /** FINE Test EnterpriseCrudController **/
    /** Test SensorCatalogCrudController **/
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

    public function test_admin_can_edit_sensor_catalog()
    {
        $this->actingAs($this->admin);

        $this->visit('/admin/sensorcatalog/'.$this->sensorCatalog->id.'/edit')
            ->see($this->sensorCatalog->name)
            ->see($this->sensorCatalog->description)
            ->see($this->sensorCatalog->min_detectable)
            ->see($this->sensorCatalog->max_detectable)
            ->see($this->sensorCatalog->sensor_type_id)
            ->see($this->sensorCatalog->brand_id)
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
    /** FINE Test SensorCatalogCrudController **/
    /** Test BrandCrudController **/
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
    /** FINE Test BrandCrudController **/
    /** Test SensorTypeCrudController **/

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
    /** FINE Test SensorTypeCrudController **/
    /** Test SiteCrudController **/
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

    public function test_admin_cant_add_site_missing_name()
    {
        $this->withExceptionHandling();
        $this->actingAs($this->admin);

        $this->visit('/admin/site/create')
            ->select($this->enterprise->id, 'enterprise_id')
            // ->type('Sensore di prova','name')
            ->type('descrizione del sensore di prova','description')
            ->attach('/img/contact-section.jpg', 'map')
            ->select($this->siteType->id, 'site_type_id')
            ->press('Salva e torna indietro')
            ->seePageIs('/admin/site/create')
            ->see('Il campo nome è richiesto.');
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

    /** FINE Test SiteCrudController **/
    /** Test SiteTypeCrudController **/

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

    /** FINE Test SiteTypeCrudController **/
    /** Test SensorCrudController **/

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
    /** FINE Test SensorCrudController **/

}
