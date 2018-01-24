<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class SensorCatalog extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'sensor_catalogs';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ["name","description","min_detectable","max_detectable","sensor_type_id","brand_id"];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getBrandName()
    {
        return $this->brand->name;
    }
    
    public function getSensorTypeName()
    {
        return $this->sensorType->name;
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function sensorType()
    {
        return $this->belongsTo(SensorType::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function sensors()
    {
        return $this->hasMany(Sensor::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */


}
