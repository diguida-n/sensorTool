<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SiteTypeRequest as StoreRequest;
use App\Http\Requests\SiteTypeRequest as UpdateRequest;

class SiteTypeCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SiteType');
        $this->crud->setRoute('/companyManager/sitetype');
        $this->crud->setEntityNameStrings('Tipo di sito', 'Tipi di siti');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD FIELDS
        $this->crud->addField([
            'name' => 'name',
            'label' => 'Nome',
            'type' => 'text',
        ], 'update/create/both');
        

        // ------ CRUD COLUMNS
        $this->crud->addColumn([
           'name' => 'name',
           'label' => "Nome"
        ]);
        
        $this->crud->enableExportButtons();
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
}
