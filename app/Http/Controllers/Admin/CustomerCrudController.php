<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest as StoreRequest;
use App\Http\Requests\CustomerRequest as UpdateRequest;
use App\Mail\AddNewUser;
use App\Models\Enterprise;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Mail;

class CustomerCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Customer');
        $this->crud->setRoute('/admin/customer');
        $this->crud->setEntityNameStrings('cliente', 'clienti');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // $this->crud->setFromDb();

        // ------ CRUD FIELDS
        $this->crud->addField([
            'name' => 'email',
            'label' => 'Email Cliente',
            'type' => 'email'
        ], 'create');
        

        // ------ CRUD COLUMNS
        $this->crud->addColumn([
           'name' => 'name',
           'label' => "Nome"
        ]);

        $this->crud->addColumn([
           'name' => 'email',
           'label' => "Email"
        ]);
       

        $this->crud->removeColumns(['password', 'remember_token','enterprise_id']); // remove an array of columns from the stack
        
        // ------ CRUD BUTTONS
        
        $this->crud->removeButton('update');
        

        $customerIds = [];
        $users = User::all();
        foreach ($users as $u) {
            if($u->hasRole('Customer'))
                $customerIds[]=$u->id;
        }
        $this->crud->addClause('whereIn','id',$customerIds);

        $this->crud->enableExportButtons();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $enterprise = Enterprise::find(auth()->user()->enterprise_id)->first();
        Mail::to($request->email)->send(new AddNewUser($enterprise,'Customer',$request->email));
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return $this->getRedirectRoute($enterprise);
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
