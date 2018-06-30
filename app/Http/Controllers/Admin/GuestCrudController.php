<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GuestRequest as StoreRequest;
use App\Http\Requests\GuestRequest as UpdateRequest;
use App\Mail\AddNewGuest;
use App\Models\Enterprise;
use App\Models\Site;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Mail;

class GuestCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Guest');
        $this->crud->setRoute('/customer/guest');
        $this->crud->setEntityNameStrings('Guest', 'Guest');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'email',
            'label' => 'Email Guest',
            'type' => 'email'
        ], 'create');

        $sites = [];
        $s = Site::where('enterprise_id',auth()->user()->enterprise_id)->get();
        foreach ($s as $site)
            $sites[$site->id]=$site->name;
        $this->crud->addField([ // select_from_array
        'name' => 'site',
        'label' => "Sito",
        'type' => 'select2_from_array',
        'options' => $sites,
        'allows_null' => false,
        // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
        
        $this->crud->addColumn([
           'name' => 'name',
           'label' => "Nome"
        ]);

        $this->crud->addColumn([
           'name' => 'email',
           'label' => "Email"
        ]);

        $this->crud->removeColumns(['password', 'remember_token','enterprise_id']); // remove an array of columns from the stack
        $this->crud->removeButton('update');

        
        $guestIds = [];
        $users = User::all();
        foreach ($users as $u) {
            if($u->hasRole('Guest') && ($u->site && $u->site->enterprise_id == auth()->user()->enterprise_id))
                $guestIds[]=$u->id;
        }
        $this->crud->addClause('whereIn','id',$guestIds);

        $this->crud->enableExportButtons();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $site = Site::find($request->site);
        Mail::to($request->email)->send(new AddNewGuest($site,'Guest',$request->email));

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return $this->getRedirectRoute($site);
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function getRedirectRoute($itemId = null)
    {
        $saveAction = \Request::input('save_action', config('backpack.crud.default_save_action', 'save_and_back'));
        $itemId = $itemId ? $itemId : \Request::input('id');

        switch ($saveAction) {
            case 'save_and_new':
                $redirectUrl = url('/admin/customer/create');
                break;
            case 'save_and_edit':
            case 'save_and_back':
            default:
                $redirectUrl = $this->crud->route;
                break;
        }
        return \Redirect::to($redirectUrl);
    }
}
