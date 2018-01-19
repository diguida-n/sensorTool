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

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function()
{
  // Backpack\CRUD: Define the resources for the entities you want to CRUD.
    CRUD::resource('enterprise', 'Admin\EnterpriseCrudController');
    CRUD::resource('/brand', 'Admin\BrandCrudController');
    CRUD::resource('/sensor', 'Admin\SensorCrudController');
    CRUD::resource('/sensortype', 'Admin\SensorTypeCrudController');
    CRUD::resource('/sensorcatalog', 'Admin\SensorCatalogCrudController');
    CRUD::resource('/sitetype', 'Admin\SiteTypeCrudController');
    CRUD::resource('/site', 'Admin\SiteCrudController');
    CRUD::resource('/message', 'Admin\MessageCrudController');
    CRUD::resource('/detection', 'Admin\DetectionCrudController');

    Route::group(['prefix' => 'user' ,'middleware'=>'admin'],function ()
    {
    	Route::get('/add-company-manager/{admin}/','Admin\EnterpriseCrudController@addCompanyManager')->name('admin.enterprise.addCompanyManager');
    });
  
  // [...] other routes
});