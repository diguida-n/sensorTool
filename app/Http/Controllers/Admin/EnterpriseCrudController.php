<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EnterpriseRequest as StoreRequest;
use App\Http\Requests\EnterpriseRequest as UpdateRequest;
use App\Mail\AddNewCompanyManager;
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
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
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
        Mail::to($request->email)->send(new AddNewCompanyManager($enterprise,'Company Manager'));
        
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
