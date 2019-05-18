<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth', 'Auth\AccessTokenController@issueToken');

Route::group(['middleware' => 'auth:api'], function () {
    
    Route::get('/me', function (Request $request) {
        $user = $request->user();
        $user['role'] = $user->roles()->first()->name;
        return ['data' => $request->user()];
    });
    
    Route::get('client', 'ClientController@index')->name('client.index');
    Route::get('client/all', 'ClientController@all')->name('client.all');
    Route::get('client/{id}', 'ClientController@show')->name('client.show');
    Route::put('client/{id}', 'ClientController@update')->name('client.update');
    Route::post('client', 'ClientController@store')->name('client.store');
    Route::delete('client/{id}', 'ClientController@destroy')->name('client.destroy');
    
    Route::get('product_category', 'ProductCategoryController@index')->name('product_category.index');
    Route::get('product_category/all', 'ProductCategoryController@all')->name('product_category.all');
    Route::get('product_category/{id}', 'ProductCategoryController@show')->name('product_category.show');
    Route::put('product_category/{id}', 'ProductCategoryController@update')->name('product_category.update');
    Route::post('product_category', 'ProductCategoryController@store')->name('product_category.store');
    Route::delete('product_category/{id}', 'ProductCategoryController@destroy')->name('product_category.destroy');
    
    Route::get('product', 'ProductController@index')->name('product.index');
    Route::get('product/all', 'ProductController@all')->name('product.all');
    Route::get('product/{id}', 'ProductController@show')->name('product.show');
    Route::put('product/{id}', 'ProductController@update')->name('product.update');
    Route::post('product', 'ProductController@store')->name('product.store');
    Route::delete('product/{id}', 'ProductController@destroy')->name('product.destroy');
    
    Route::get('order', 'OrderController@index')->name('order.index');
    Route::get('order/{id}', 'OrderController@show')->name('order.show');
    Route::put('order/{id}', 'OrderController@update')->name('order.update');
    Route::post('order', 'OrderController@store')->name('order.store');
    Route::delete('order/{id}', 'OrderController@destroy')->name('order.destroy');
    
    Route::group(['middleware' => ['role:root|administrator']], function () {
        
        Route::get('user', 'UserController@index')->name('user.index');
        Route::get('user/{id}', 'UserController@show')->name('user.show');
        Route::put('user/{id}', 'UserController@update')->name('user.update');
        Route::post('user', 'UserController@store')->name('user.store');
        Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');
        
        Route::group(['middleware' => ['role:root']], function () {
            
            Route::get('company', 'CompanyController@index')->name('company.index');
            Route::get('company/all', 'CompanyController@all')->name('company.all');
            Route::get('company/{id}', 'CompanyController@show')->name('company.show');
            Route::put('company/{id}', 'CompanyController@update')->name('company.update');
            Route::post('company', 'CompanyController@store')->name('company.store');
            Route::delete('company/{id}', 'CompanyController@destroy')->name('company.destroy');
            
        });
        
    });
    
    Route::get("cep/{cep}", function ($cep) {
        return ['data' => cep($cep)->toArray()->result()];
    });
    
    Route::get('state', function () {
        return ['data' => \App\Models\State::orderBy('name')->get(['id', 'name', 'abbr'])];
    });
    
    Route::get('city/{id}', function ($id) {
        return ['data' => \App\Models\City::orderBy('name')->where('state_id', '=', $id)->get(['id', 'name'])];
    });
    
});
