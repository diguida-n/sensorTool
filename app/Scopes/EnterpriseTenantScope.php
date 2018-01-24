<?php

namespace App\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EnterpriseTenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder)
    {
        
        $user = Auth::user();
        if(!$user){
            return;
        }
        $enterprise_id = $user->enterprise_id;
        if($user->isCompanyManager())
            $builder->where('id',$enterprise_id);
    }
}