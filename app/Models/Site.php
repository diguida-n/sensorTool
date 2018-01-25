<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\SiteTenantScope;

class Site extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'sites';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name','address','map','description','enterprise_id','site_type_id'];
    // protected $hidden = [];
    // protected $dates = [];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getEnterpriseName()
    {
        return $this->enterprise->businessName;
    }
    public function getSiteTypeName()
    {
        return $this->siteType->name;
    }

    public function getImage()
    {
        if($this->map)
            return '<img src="'.$this->map.'" alt="Red dot" height="50">';
        return '-';
    }

    public function getAddNewSensor()
    {
        return '<a href="'.url('companyManager/sensor/create').'" class="btn btn-xs btn-default"><i aria-hidden="true" class="fa fa-assistive-listening-systems"></i> + Sensore</a>';
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function siteType()
    {
        return $this->belongsTo(SiteType::class);
    }
    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }
    public function sensors()
    {
        return $this->hasMany(Sensor::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SiteTenantScope);
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
