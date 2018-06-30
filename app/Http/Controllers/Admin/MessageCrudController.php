<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MessageRequest as StoreRequest;
use App\Http\Requests\MessageRequest as UpdateRequest;

class MessageCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Message');
        $this->crud->setRoute('/customer/message');
        $this->crud->setEntityNameStrings('Messaggio', 'Messaggi');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD FIELDS
        $this->crud->removeField('enterprise_id', 'both');


        $this->crud->addField([
            'name' => 'description',
            'label' => 'Descrizione',
            'type' => 'ckeditor',
        ], 'update/create/both');
        $this->crud->addField([
            'name'        => 'malfunction', // the name of the db column
            'label'       => 'Malfunzionamento', // the input label
            'type'        => 'radio',
            'options'     => [ // the key will be stored in the db, the value will be shown as label; 
                                0 => "No",
                                1 => "Si"
                            ],
            'inline'      => true, // show the radios all on the same line?
        ], 'update/create/both');
        $this->crud->addField([
            'name'        => 'exception', // the name of the db column
            'label'       => 'Eccezione', // the input label
            'type'        => 'radio',
            'options'     => [ // the key will be stored in the db, the value will be shown as label; 
                                0 => "No",
                                1 => "Si"
                            ],
            'inline'      => true, // show the radios all on the same line?
        ], 'update/create/both');
        $this->crud->addField([
            'label' => "Catalogo Sensori",
            'type' => 'select2',
            'name' => 'sensor_catalog_id', // the db column for the foreign key
            'entity' => 'sensorCatalog', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\SensorCatalog", // foreign key model
            "attributes"=>['required'=>true]
        ], 'update/create/both');
        

        // ------ CRUD COLUMNS
        $this->crud->removeColumns(['malfunction','exception','description','enterprise_id']);

        $this->crud->addColumn([
            'name' => 'sensor_catalog_id',
            'label' => 'Sensore',
            'type' => "model_function",
            'function_name' => 'getSensorName',
        ]);

        $this->crud->addColumn([
            'name' => 'malfunction',
            'label' => 'Malfunzionamento',
            'type' => 'boolean',
            'options' => [0 => 'No', 1 => 'Si']
        ]);

        $this->crud->addColumn([
            'name' => 'exception',
            'label' => 'Eccezione',
            'type' => 'boolean',
            'options' => [0 => 'No', 1 => 'Si']
        ]);
        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Descrizione',
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Data acquisizione',
            'type' => 'datetime'
        ]);

       
        $this->crud->removeAllButtons();
       

        if(auth()->user()->isGuest()){
            $sensors = auth()->user()->site->sensors;
            $ids = [];
            foreach ($sensors as $sensor) {
                foreach($sensor->detections as $d){
                    $ids[]=$d->message->id;
                }
            }
            $this->crud->addClause('whereIn','id',$ids);
        }
       
       $this->crud->enableExportButtons();
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
