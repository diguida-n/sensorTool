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

Route::get('/', 'HomeController@home')->name('home');
Route::get('/admin/register','Auth\RegisterUserController@showRegistrationForm')->name('backpack.auth.register');

Route::post('/submitInfoRequest', 'HomeController@submitInfoRequest')->name('submitInfoRequest');
Route::get('/admin/register/{cryptedData?}','Auth\RegisterUserController@showRegistrationForm')->name('registerUser');
Route::post('/admin/register','Auth\RegisterUserController@register')->name('storeUser');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function()
{
    // Backpack\CRUD: Define the resources for the entities you want to CRUD.
    CRUD::resource('enterprise', 'Admin\EnterpriseCrudController')->with(function(){
    // add extra routes to this resource
        Route::get('/user/add-company-manager/{enterprise}/','Admin\EnterpriseCrudController@addCompanyManager')->name('admin.enterprise.addCompanyManager');
        Route::post('/user/store-company-manager/{enterprise}/','Admin\EnterpriseCrudController@storeCompanyManager')->name('admin.enterprise.storeCompanyManager');
    });
    CRUD::resource('/brand', 'Admin\BrandCrudController');
    CRUD::resource('/sensor', 'Admin\SensorCrudController');
    CRUD::resource('/sensortype', 'Admin\SensorTypeCrudController');
    CRUD::resource('/sensorcatalog', 'Admin\SensorCatalogCrudController');
    CRUD::resource('/sitetype', 'Admin\SiteTypeCrudController');
    CRUD::resource('/site', 'Admin\SiteCrudController');
    CRUD::resource('/message', 'Admin\MessageCrudController');
    CRUD::resource('/detection', 'Admin\DetectionCrudController');
  
  // [...] other routes
});