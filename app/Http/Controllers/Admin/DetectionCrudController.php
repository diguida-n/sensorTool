<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DetectionRequest as StoreRequest;
use App\Http\Requests\DetectionRequest as UpdateRequest;
use App\Models\Site;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class DetectionCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Detection');
        $this->crud->setRoute('/customer/detection');
        $this->crud->setEntityNameStrings('Dato', 'Dati');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD FIELDS
        $this->crud->removeField('enterprise_id', 'both');
        $this->crud->addField([
            'name' => 'value',
            'label' => 'Valore registrato',
            'type' => 'number',
            'attributes' => ["step" => 0.01,"max"=>999999.99,"min"=>-999999.99], // allow decimals
        ], 'update/create/both');
        $this->crud->addField([
            'label' => "Sensore",
            'type' => 'select2',
            'name' => 'sensor_id', // the db column for the foreign key
            'entity' => 'Sensor', // the method that defines the relationship in your Model
            'attribute' => 'sensor_catalog_id', // foreign key attribute that is shown to user
            'model' => "App\Models\Sensor", // foreign key model
            "attributes"=>['required'=>true]
        ], 'update/create/both');
        $this->crud->addField([
            'label' => "Messaggio",
            'type' => 'select2',
            'name' => 'message_id', // the db column for the foreign key
            'entity' => 'Message', // the method that defines the relationship in your Model
            'attribute' => 'description', // foreign key attribute that is shown to user
            'model' => "App\Models\Message", // foreign key model
            "attributes"=>['required'=>true]
        ], 'update/create/both');
        

        // ------ CRUD COLUMNS
        $this->crud->addColumn([
            'name' => 'value',
            'label' => 'Valore registrato',
            'type' => "model_function",
            'function_name' => 'getValueDecodedForTable',
        ]);
        $this->crud->addColumn([
            'name' => 'sensor_id',
            'label' => 'Sensore',
            'type' => "model_function",
            'function_name' => 'getSensorName',
        ]);
        $this->crud->addColumn([
            'name' => 'message_id',
            'label' => 'Messaggio',
            'type' => "model_function",
            'function_name' => 'getMessageDescription',
        ]);
        $this->crud->addColumn([
            'name' => 'site',
            'label' => 'Sito',
            'type' => "model_function",
            'function_name' => 'getSite',
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Data acquisizione',
            'type' => 'datetime'
        ]);
        $this->crud->removeColumn('enterprise_id'); // remove a column from the stack
       

        // ------ CRUD BUTTONS
        
        $this->crud->removeAllButtons();
        
        if(auth()->user()->isGuest()){
            $detectionsIds = array_values(auth()->user()->site->sensors->pluck('id')->toArray());
            $this->crud->addClause('whereIn','sensor_id',$detectionsIds);
        }
        
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
