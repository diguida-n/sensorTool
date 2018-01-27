<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EnterpriseRequest as StoreRequest;
use App\Http\Requests\EnterpriseRequest as UpdateRequest;
use App\Mail\AddNewUser;
use App\Models\Enterprise;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnterpriseCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Enterprise');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/enterprise');
        $this->crud->setEntityNameStrings('Impresa', 'Imprese');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD FIELDS
        $this->crud->addField([
            "name" => "businessName",
            "label" => "Nome Impresa",
        ]);

        $this->crud->addField([
            "name" => "address",
            "label" => "Indirizzo",
            'type' => 'address',
            // optional
            'store_as_json' => true
        ]);

        $this->crud->addField([
            "name" => "vatNumber",
            "label" => "Partita Iva",
        ]);
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        $this->crud->addColumn([
            "name" => "businessName",
            "label" => "Nome Impresa",
        ]);

        $this->crud->addColumn([
            "name" => "address",
            "label" => "Indirizzo",
            "type" => "model_function",
            "function_name" => "getAddress"
        ]);

        $this->crud->addColumn([
            "name" => "vatNumber",
            "label" => "Partita Iva",
        ]);

        // ------ CRUD BUTTONS
        $this->crud->addButtonFromModelFunction("line",'addNewCompanyManager','getAddNewCompanyManager','end');
        
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function addCompanyManager($enterpriseId)
    {
        $data['crud'] = $this->crud;
        $data['saveAction'] = $this->getSaveAction();
        unset($data['saveAction']['options']['save_and_edit']);
        $data['fields'] = [
            "email" => [
                "name" => "email",
                "label" => "Email",
                'type' => 'text',
            ]
        ];
        $data['title'] = 'Aggiungi Responsabile Aziendale'.' '.$this->crud->entity_name;
        $data['enterpriseId'] = $enterpriseId;
        // dd($this->crud->getFields('create'));
        return view('admin.addCompanyManager',$data);
    }

    public function storeCompanyManager(Request $request,$enterprise)
    {
        $this->validate($request,['email'=> 'required|email']);
        $enterprise = Enterprise::find($enterprise)->first();
        Mail::to($request->email)->send(new AddNewUser($enterprise,'Company Manager',$request->email));
        
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return $this->getRedirectRoute($enterprise);
    }

    private function getRedirectRoute($itemId = null)
    {
        $saveAction = \Request::input('save_action', config('backpack.crud.default_save_action', 'save_and_back'));
        $itemId = $itemId ? $itemId : \Request::input('id');

        switch ($saveAction) {
            case 'save_and_new':
                $redirectUrl = route('admin.enterprise.addCompanyManager',$itemId);
                break;
            case 'save_and_edit':
                $redirectUrl = $this->crud->route.'/'.$itemId.'/edit';
                if (\Request::has('locale')) {
                    $redirectUrl .= '?locale='.\Request::input('locale');
                }
                break;
            case 'save_and_back':
            default:
                $redirectUrl = $this->crud->route;
                break;
        }
        return \Redirect::to($redirectUrl);
    }
}
