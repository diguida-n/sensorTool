<?php

namespace App\Models;

use App\Scopes\EnterpriseTenantScope;
use App\User;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'enterprises';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['businessName','address','vatNumber'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getAddress()
    {
        $address = json_decode($this->address);

        if($address)
            return $address->name.", "."".$address->administrative.", ".$address->city.", ".$address->country;
        return "";
    }

    public function getAddNewCustomer()
    {
        return '<a href="'.route('admin.enterprise.addCustomer',$this->id).'" class="btn btn-xs btn-default"><i aria-hidden="true" class="fa fa-user"></i> + Cliente</a>';
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function users()
    {
        return $this->hasMany(User::class(),'enterprise_id');
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EnterpriseTenantScope);
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
