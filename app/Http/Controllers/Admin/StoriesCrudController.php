<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoriesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


/**
 * Class StoriesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StoriesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Stories::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stories');
        CRUD::setEntityNameStrings('stories', 'stories');

        $this->crud->addClause('where', 'user_id', '=', backpack_user()->id);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('title');
        //Added code to show the user name
        $this->crud->addColumn([
            'name'  => 'user_name.fullname',
            'label' => 'User',
            'type'  => 'relationship'
        ]);
        CRUD::column('slug');
        CRUD::column('published_date');
        CRUD::column('status');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoriesRequest::class);
        CRUD::field('title');
        CRUD::field('slug');
        $this->crud->addField([
            'name' => 'status', // The db column name
            'label' => "Status", // Table column heading
            'type' => 'select_from_array',
            'options' => ['Active' => 'Active', 'Inactive' => 'Inactive', 'Archived' => 'Archived'],
        ]);

        $this->crud->addField([
            'name'  => 'user_id',
            'type'  => 'hidden',
            'value' => backpack_user()->id
        ]);

        $this->crud->addField([
            'name'  => 'published_date',
            'type'  => 'hidden',
            'value' => date('Y-m-d H:i:s', time())
        ]);
        $this->crud->addField([
            'name'  => 'content',
            'label' => 'Content',
            'type'  => 'ckeditor'
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store()
    {
        // do something before validation, before save, before everything; for example:
        // $this->crud->addField(['type' => 'hidden', 'name' => 'author_id']);
        // $this->crud->removeField('password_confirmation');

        // Note: By default Backpack ONLY saves the inputs that were added on page using Backpack fields.
        // This is done by stripping the request of all inputs that do NOT match Backpack fields for this
        // particular operation. This is an added security layer, to protect your database from malicious
        // users who could theoretically add inputs using DeveloperTools or JavaScript. If you're not properly
        // using $guarded or $fillable on your model, malicious inputs could get you into trouble.

        // However, if you know you have proper $guarded or $fillable on your model, and you want to manipulate 
        // the request directly to add or remove request parameters, you can also do that.
        // We have a config value you can set, either inside your operation in `config/backpack/crud.php` if
        // you want it to apply to all CRUDs, or inside a particular CrudController:

        // The above will make Backpack store all inputs EXCEPT for the ones it uses for various features.
        // So you can manipulate the request and add any request variable you'd like.
        // $this->crud->getRequest()->request->add(['author_id'=> backpack_user()->id]);
        // $this->crud->getRequest()->request->remove('password_confirmation');
        $slug = $this->crud->getRequest()->request->get('slug');
        $slug = str_replace('/','',$slug);
        $this->crud->getRequest()->request->set('slug', str_replace(' ','-',$slug));
        if (isset($this->crud->getRequest()->status) && $this->crud->getRequest()->status != 'Active') {
            $this->crud->getRequest()->request->set('published_date', null);
        }
        $response = $this->traitStore();
        // do something after save
        return $response;
    }
}
