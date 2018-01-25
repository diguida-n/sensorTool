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
        $this->crud->setRoute('/employee/message');
        $this->crud->setEntityNameStrings('Messaggio', 'Tipi di messaggio');

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
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

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

        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        $this->crud->removeAllButtons();
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });

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
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
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
