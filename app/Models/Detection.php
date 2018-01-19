<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use App\Scopes\DetectionTenantScope;
use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'detections';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        "value","sensor_id","message_id","enterprise_id"
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
        return $this->sensor->getSensorName();
    }
    public function getMessageDescription()
    {
        return $this->message->description;
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new DetectionTenantScope);
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
