<?php

namespace App;

use App\Models\Enterprise;
use App\Models\Site;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Backpack\CRUD\CrudTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one

use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    use Notifiable;
    
    use CrudTrait; // <----- this
    use HasRoles; // <------ and this

    use Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','enterprise_id','site_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
     /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function isGuest()
    {
        return $this->HasRole("Guest")?true:false;
    }
    public function isEmployee()
    {
        return $this->HasRole("Employee")?true:false;
    }
    public function isCompanyManager()
    {
        return $this->HasRole("Company Manager")?true:false;
    }
    public function isAdmin()
    {
        return $this->HasRole("Admin")?true:false;
    }

    public function getImpersonateButton()
    {
        if(auth()->user()->id!=$this->id)
            return "<a class='btn btn-xs btn-default' href='". route('impersonate', $this->id)  ."'><i class='fa fa-eye'></i>&nbsp;Impersonifica</a>";
    }
}
