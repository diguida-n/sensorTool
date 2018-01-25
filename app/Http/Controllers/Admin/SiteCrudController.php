<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SiteRequest as StoreRequest;
use App\Http\Requests\SiteRequest as UpdateRequest;

class SiteCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Site');
        $this->crud->setRoute('/companyManager/site');
        $this->crud->setEntityNameStrings('Sito', 'Siti');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD FIELDS

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Nome sito',
        ], 'update/create/both');

        $this->crud->addField([
            'name' => 'address',
            'label' => 'Indirizzo',
            'type' => 'address',
        ], 'update/create/both');

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Descrizione',
            'type' => 'ckeditor',
        ], 'update/create/both');

        $this->crud->addField([
            'name' => 'map',
            'label' => 'Mappa',
            'type' => 'image',
            'upload' => true,
            'crop' => false, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1,
        ], 'update/create/both');


        $this->crud->addField([
            'label' => "Tipo Sito",
            'type' => 'select2',
            'name' => 'site_type_id', // the db column for the foreign key
            'entity' => 'siteType', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\SiteType", // foreign key model
            "attributes"=>['required'=>true]
        ], 'update/create/both');
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        $this->crud->removeField('enterprise_id', 'both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');
        // ------ CRUD COLUMNS
        $this->crud->addColumn([
           'name' => 'name',
           'label' => "Nome"
        ]);
        $this->crud->addColumn([
           'name' => 'address',
           'label' => "Indirizzo"
        ]);
        $this->crud->addColumn([
           'name' => 'description',
           'label' => "Descrizione"
        ]);

        $this->crud->addColumn([
            'name' => 'site_type_id',
            'label' => 'Tipo Sito',
            'type' => "model_function",
            'function_name' => 'getSiteTypeName',
        ]);

        $this->crud->addColumn([
            'name' => 'map', // The db column name
            'label' => "Mappa", // Table column heading
            'type' => 'model_function',
            'function_name' => 'getImage'

        ]);

        $this->crud->removeColumns(['enterprise_id']); // remove an array of columns from the stack
        $this->crud->addButtonFromModelFunction("line",'addNewSensor','getAddNewSensor','end');

        
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $request['enterprise_id']=auth()->user()->enterprise_id;
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $request['enterprise_id']=auth()->user()->enterprise_id;
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
