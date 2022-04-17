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
/*
Route::prefix('modulemanager')->group(function() {
    Route::get('/', 'ModulemanagerController@index');
});
*/

/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Modulemanager\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'app'], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Posts Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'modulemanager';
    $controller_name = 'ModulemanagerController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::get("$module_name/disable", ['as' => "$module_name.disable", 'uses' => "$controller_name@disable"]);
    Route::get("$module_name/refresh", ['as' => "$module_name.refresh", 'uses' => "$controller_name@refresh"]);
    Route::get("$module_name/update_module/{module_name}", ['as' => "$module_name.update_module", 'uses' => "$controller_name@artisanUpdate"]);
    Route::get("$module_name/settings/{name}", ['as' => "$module_name.settings", 'uses' => "$controller_name@settings"]);

    Route::get("$module_name/disable_module/{module_name}", ['as' => "$module_name.disable_module", 'uses' => "$controller_name@disable"]);
    Route::get("$module_name/enable_module/{module_name}", ['as' => "$module_name.enable_module", 'uses' => "$controller_name@enable"]);
    Route::post("$module_name/delete_module/{module_name}", ['as' => "$module_name.delete_module", 'uses' => "$controller_name@deleteModule"]);

    Route::resource("$module_name", "$controller_name");
    Route::resource("$module_name"."/builder", "ModuleBuilderController", ['as' => 'module_builder']);


});
