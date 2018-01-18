<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use App\Scopes\SensorTenantScope;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'sensors';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['min_attended','max_attended','longitude','latitude','site_id','sensor_catalog_id','enterprise_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getSensorName()
    {
        return $this->sensorCatalog->name;
    }
    public function getSiteName()
    {
        return $this->site->name;
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function sensorCatalog()
    {
        return $this->belongsTo(SensorCatalog::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SensorTenantScope);
    }
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
