<?php

namespace Tests\Unit;

use App\Models\SensorCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    public function test_add_sensor_to_catalog()
    {

    	$fields = [
    		'name' => 'Sensore a caso',
    		'description' => ' descrizione Sensore a caso',
    		'min_detectable' => 0,
    		'max_detectable' => 40,
    		'sensor_type_id' => $this->sensorType->id,
    		'brand_id' => $this->brand->id,
    	];
    	SensorCatalog::create($fields);

    	$this->assertEquals(1, SensorCatalog::where($fields)->count());
    }

    public function test_edit_sensor_to_catalog()
    {

    	$sensor=SensorCatalog::find($this->sensorCatalog->id);
    	$sensor->name = 'ora è modificato';
    	$sensor->save();
    	$this->assertEquals(1, SensorCatalog::where('name', 'ora è modificato')->count());
    }
}
