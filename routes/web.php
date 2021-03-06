<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::impersonate();

Route::get('/register', function ()
{
    abort(403, trans('backpack::base.registration_closed'));
});

Route::get('/login', function ()
{
    abort(403, trans('backpack::base.registration_closed'));
});
Route::get('/', 'HomeController@home')->name('home');
Route::get('/admin/register','Auth\RegisterUserController@showRegistrationForm')->name('backpack.auth.register');

Route::post('/submitInfoRequest', 'HomeController@submitInfoRequest')->name('submitInfoRequest');
Route::get('/admin/register/{cryptedData?}','Auth\RegisterUserController@showRegistrationForm')->name('registerUser');
Route::post('/admin/register','Auth\RegisterUserController@register')->name('storeUser');
Route::get('/admin/dashboard',function()
{
    return redirect(url('/customer/dashboard'));
});
Route::get('/admin',function()
{
    if(auth()->user()){
        if(auth()->user()->isCustomer() || (auth()->user()->isGuest() && auth()->user()->site_id))
            return redirect(url('/customer/dashboard'));
        if(auth()->user()->isAdmin())
            return redirect(url('/admin/site'));
    }
    return redirect(url('/'));
});
Route::group(['prefix'=>'customer','middleware'=>'auth.customer'],function()
{
    Route::get('/dashboard','DetectionController@dashboard');
    Route::post('/getSensorsData/{site}','DetectionController@getSensorsData');
    CRUD::resource('/message', 'Admin\MessageCrudController');
    CRUD::resource('/detection', 'Admin\DetectionCrudController');
    CRUD::resource('/guest', 'Admin\GuestCrudController');
    
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function()
{
    // Backpack\CRUD: Define the resources for the entities you want to CRUD.
    CRUD::resource('/enterprise', 'Admin\EnterpriseCrudController')->with(function(){
    // add extra routes to this resource
        Route::get('/user/add-customer/{enterprise}/','Admin\EnterpriseCrudController@addCustomer')->name('admin.enterprise.addCustomer');
        Route::post('/user/store-customer/{enterprise}/','Admin\EnterpriseCrudController@storeCustomer')->name('admin.enterprise.storeCustomer');

    });

    CRUD::resource('/sensor', 'Admin\SensorCrudController');
    CRUD::resource('/sitetype', 'Admin\SiteTypeCrudController');
    CRUD::resource('/site', 'Admin\SiteCrudController');
    CRUD::resource('/customer', 'Admin\CustomerCrudController');

    Route::group([
                // 'namespace'  => 'Backpack\PermissionManager\app\Http\Controllers',
        ], function () {
            CRUD::resource('/permission', 'Admin\PermissionCrudControllerCustom');
            CRUD::resource('/role', 'Admin\RoleCrudControllerCustom');
            CRUD::resource('/user', 'Admin\UserCrudControllerCustom');
        });
      
        CRUD::resource('/brand', 'Admin\BrandCrudController');
    CRUD::resource('/sensortype', 'Admin\SensorTypeCrudController');
    CRUD::resource('/sensorcatalog', 'Admin\SensorCatalogCrudController');
      
    });
