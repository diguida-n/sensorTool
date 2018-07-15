<?php

namespace App\Http\Controllers\Admin;

use App\Models\Site;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SensorRequest as StoreRequest;
use App\Http\Requests\SensorRequest as UpdateRequest;

class SensorCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Sensor');
        $this->crud->setRoute('/admin/sensor');
        $this->crud->setEntityNameStrings('Sensore', 'Sensori');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD FIELDS
        
        $this->crud->removeField('enterprise_id', 'both');
        $this->crud->addField([
            'name' => 'min_attended',
            'label' => 'Minimo atteso',
            'type' => 'number',
            'attributes' => ["step" => 0.01,"max"=>999999.99,"min"=>-999999.99], // allow decimals
        ], 'update/create/both');
        $this->crud->addField([
            'name' => 'max_attended',
            'label' => 'Massimo atteso',
            'type' => 'number',
            'attributes' => ["step" => 0.01,"max"=>999999.99,"min"=>-999999.99], // allow decimals
        ], 'update/create/both');
        $this->crud->addField([
            'name' => 'latitude',
            'label' => 'Latitudine',
            'type' => 'number',
            'attributes' => ["step" => 0.001,"max"=>9999.9999999,"min"=>-9999.9999999], // allow decimals
        ], 'update/create/both');
        $this->crud->addField([
            'name' => 'longitude',
            'label' => 'Longitudine',
            'type' => 'number',
            'attributes' => ["step" => 0.001,"max"=>9999.9999999,"min"=>-9999.9999999], // allow decimals
        ], 'update/create/both');
        $this->crud->addField([
            'label' => "Sito",
            'type' => 'select',
            'name' => 'site_id', // the db column for the foreign key
            'entity' => 'siteType', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Site", // foreign key model
            "attributes"=>['required'=>true]
        ], 'update/create/both');
        $this->crud->addField([
            'label' => "Catalogo Sensori",
            'type' => 'select',
            'name' => 'sensor_catalog_id', // the db column for the foreign key
            'entity' => 'sensorCatalog', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\SensorCatalog", // foreign key model
            "attributes"=>['required'=>true]
        ], 'update/create/both');
        // ------ CRUD COLUMNS
        $this->crud->addColumn([
            'name' => 'min_attended',
            'label' => 'Minimo atteso',
        ]);
        $this->crud->addColumn([
            'name' => 'max_attended',
            'label' => 'Massimo atteso',
        ]);
        $this->crud->addColumn([
            'name' => 'latitude',
            'label' => 'Latitudine',
        ]);
        $this->crud->addColumn([
            'name' => 'longitude',
            'label' => 'Longitudine',
        ]);
        $this->crud->addColumn([
            'name' => 'site_id',
            'label' => 'Sito',
            'type' => "model_function",
            'function_name' => 'getSiteName',
        ]);
        $this->crud->addColumn([
            'name' => 'sensor_catalog_id',
            'label' => 'Sensore',
            'type' => "model_function",
            'function_name' => 'getSensorName',
        ]);
        
        $this->crud->removeColumn('enterprise_id'); // remove a column from the stack
        
        // ------ CRUD BUTTONS
       
        $this->crud->removeButton("create");
        
        $this->crud->enableExportButtons();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $request['enterprise_id']=Site::find($request->site_id)->enterprise_id;
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $request['enterprise_id']=Site::find($request->site_id)->enterprise_id;
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
