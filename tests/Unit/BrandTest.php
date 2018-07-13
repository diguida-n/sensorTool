<?php

namespace Tests\Unit;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BrandTest extends TestCase
{

	public function test_go_to_brand_panel ()
	{
		$this->actingAs($this->admin);
		$this->visit('/admin/brand')
            ->see('Aggiungi Brand');
	}


	public function test_add_new_brand()
	{
		$fields = [];
		$fields['name'] = 'Sigma';

		Brand::create($fields);

		$this->assertEquals(1, Brand::where($fields)->count());
	}


	public function test_edit_brand()
	{

		$brand = Brand::first();
		$brand->name = 'Sigma SPA';
		$brand->save();
		
		$this->assertEquals(1, Brand::where('name', $brand->name)->count());
	}

	public function test_delete_brand()
	{
		$brand = Brand::first();
		$brand->delete();

		$this->assertEquals(0, count(Brand::find($brand->id)));
	}

}
