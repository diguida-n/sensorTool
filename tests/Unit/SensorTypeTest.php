<?php

namespace Tests\Unit;

use App\Models\SensorType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SensorTypeTest extends TestCase
{

    
    public function test_go_to_sensor_type_panel ()
	{
		$this->actingAs($this->admin);
		$this->visit('/admin/sensortype')
            ->see('Aggiungi Tipo di sensore');
	}


	public function test_add_new_sensor_type()
	{
		$fields = [];
		$fields['name'] = 'Termometro del sito';
		$fields['description'] = 'Serve per misurare la temperatura';

		$this->sensorType = SensorType::create($fields);

		$this->assertEquals(1, SensorType::where($fields)->count());
	}


	public function test_edit_enterprise()
	{
		$this->sensorType->description = 'Serve a misurare la temperatura di un sito';
		$this->sensorType->save();
		
		$this->assertEquals(1, SensorType::where('description', $this->sensorType->description)->count());
	}

	public function test_delete_enterprise()
	{
		$this->sensorType->delete();

		$this->assertEquals(0, count(SensorType::find($this->siteType->id)));
	}
}
