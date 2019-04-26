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

    Route::get('client', 'ClientController@index')->name('client.index');
    Route::get('client/{id}', 'ClientController@edit')->name('client.edit');
    Route::put('client/{id}', 'ClientController@update')->name('client.update');
    Route::post('client', 'ClientController@store')->name('client.store');
    Route::delete('client/{id}', 'ClientController@destroy')->name('client.destroy');

    Route::get('product', 'ProductController@index')->name('product.index');
    Route::get('product/all', 'ProductController@all')->name('product.all');
    Route::get('product/{id}', 'ProductController@edit')->name('product.edit');
    Route::put('product/{id}', 'ProductController@update')->name('product.update');
    Route::post('product', 'ProductController@store')->name('product.store');
    Route::delete('product/{id}', 'ProductController@destroy')->name('product.destroy');

    Route::get('order', 'OrderController@index')->name('order.index');
    Route::get('order/{id}', 'OrderController@edit')->name('order.edit');
    Route::put('order/update/{id}', 'OrderController@update')->name('order.update');
    Route::post('order/store', 'OrderController@store')->name('order.store');
    Route::delete('order/{id}', 'OrderController@destroy')->name('order.destroy');

    Route::group(['middleware' => ['role:root|administrator']], function () {

        Route::get('user', 'UserController@index')->name('user.index');
        Route::get('user/{id}', 'UserController@edit')->name('user.edit');
        Route::put('user/{id}', 'UserController@update')->name('user.update');
        Route::post('user', 'UserController@store')->name('user.store');
        Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');

        Route::group(['middleware' => ['role:root']], function () {

            Route::get('company', 'CompanyController@index')->name('company.index');
            Route::get('company/all', 'CompanyController@all')->name('company.all');
            Route::get('company/{id}', 'CompanyController@edit')->name('company.edit');
            Route::put('company/{id}', 'CompanyController@update')->name('company.update');
            Route::post('company', 'CompanyController@store')->name('company.store');
            Route::delete('company/{id}', 'CompanyController@destroy')->name('company.destroy');

        });

    });

    Route::get("cep/{cep}", function ($cep) {
        return cep($cep)->toJson()->result();
    });

    Route::get('cities/{id}', function ($id) {
        return \App\Models\City::orderBy('name')->where('state_id', '=', $id)->get(['id', 'name']);
    });

});
