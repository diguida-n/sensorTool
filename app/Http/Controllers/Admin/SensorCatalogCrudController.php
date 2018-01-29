<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SensorCatalogRequest as StoreRequest;
use App\Http\Requests\SensorCatalogRequest as UpdateRequest;

class SensorCatalogCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SensorCatalog');
        $this->crud->setRoute('/admin/sensorcatalog');
        $this->crud->setEntityNameStrings('Catalogo Sensori', 'Catalogo sensori');

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

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Descrizione',
            'type' => 'ckeditor',
        ], 'update/create/both');

        $this->crud->addField([
            'name' => 'min_detectable',
            'label' => 'Minimo registrabile',
            'type' => 'number',
            'attributes' => ["step" => 0.01,"max"=>999999.99,"min"=>-999999.99], // allow decimals
        ], 'update/create/both');
        $this->crud->addField([
            'name' => 'max_detectable',
            'label' => 'Massimo registrabile',
            'type' => 'number',
            'attributes' => ["step" => 0.01,"max"=>999999.99,"min"=>-999999.99], // allow decimals
        ], 'update/create/both');
        $this->crud->addField([
            'label' => "Tipo Sensore",
            'type' => 'select2',
            'name' => 'sensor_type_id', // the db column for the foreign key
            'entity' => 'sensorType', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\SensorType", // foreign key model
            "attributes"=>['required'=>true]
        ], 'update/create/both');
        $this->crud->addField([
            'label' => "Brand sensore",
            'type' => 'select2',
            'name' => 'brand_id', // the db column for the foreign key
            'entity' => 'brand', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Brand", // foreign key model
            "attributes"=>['required'=>true]
        ], 'update/create/both');
        

        // ------ CRUD COLUMNS
        $this->crud->addColumn([
           'name' => 'name',
           'label' => "Nome"
        ]);
        $this->crud->addColumn([
           'name' => 'description',
           'label' => "Descrizione"
        ]);
        $this->crud->addColumn([
            'name' => 'min_detectable',
            'label' => 'Minimo registrabile',
        ]);
        $this->crud->addColumn([
            'name' => 'max_detectable',
            'label' => 'Massimo registrabile',
        ]);
        $this->crud->removeColumn('sensor_type_id'); // remove a column from the stack

        $this->crud->addColumn([
            'name' => 'brand_id',
            'label' => 'Brand Sensore',
            'type' => "model_function",
            'function_name' => 'getBrandName',
        ]);
        $this->crud->addColumn([
            'name' => 'sensor_type_id',
            'label' => 'Tipo Sensore',
            'type' => "model_function",
            'function_name' => 'getSensorTypeName',
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
