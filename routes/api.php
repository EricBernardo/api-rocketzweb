<?php

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

    Route::group(['middleware' => ['role:root|administrator|client']], function () {

        Route::get('nfe/{id}', 'NotaFiscalEletronicaController@show')->name('nfe.show');
        Route::get('nfe/{id}/protocol', 'NotaFiscalEletronicaController@protocol')->name('nfe.protocal');
        Route::post('nfe/{id}', 'NotaFiscalEletronicaController@store')->name('nfe.store');
        Route::delete('nfe/{id}', 'NotaFiscalEletronicaController@destroy')->name('nfe.destroy');

        Route::get('profile', 'ProfileController@index')->name('profile.index');
        Route::put('profile', 'ProfileController@update')->name('profile.update');
        Route::put('profile/choose-company', 'ProfileController@chooseCompany')->name('profile.choose.company');

        Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');

        Route::get('product/all', 'ProductController@all')->name('product.all');

        Route::get('order', 'OrderController@index')->name('order.index');
        Route::get('order/{id}', 'OrderController@show')->name('order.show');
        Route::put('order/{id}', 'OrderController@update')->name('order.update');
        Route::post('order', 'OrderController@store')->name('order.store');
        Route::delete('order/{id}', 'OrderController@destroy')->name('order.destroy');

        Route::get('company/all', 'CompanyController@all')->name('company.all');
        Route::get('shipping_company/all', 'ShippingCompanyController@all')->name('shipping_company.all');
        Route::get('shipping_company_vehicle/all', 'ShippingCompanyVehicleController@all')->name('shipping_company_vehicle.all');

        Route::group(['middleware' => ['role:root|administrator']], function () {

            Route::get('shipping_company', 'ShippingCompanyController@index')->name('shipping_company.index');
            Route::get('shipping_company/{id}', 'ShippingCompanyController@show')->name('shipping_company.show');
            Route::put('shipping_company/{id}', 'ShippingCompanyController@update')->name('shipping_company.update');
            Route::post('shipping_company', 'ShippingCompanyController@store')->name('shipping_company.store');
            Route::delete('shipping_company/{id}', 'ShippingCompanyController@destroy')->name('shipping_company.destroy');

            Route::get('shipping_company_vehicle', 'ShippingCompanyVehicleController@index')->name('shipping_company_vehicle.index');
            Route::get('shipping_company_vehicle/{id}', 'ShippingCompanyVehicleController@show')->name('shipping_company_vehicle.show');
            Route::put('shipping_company_vehicle/{id}', 'ShippingCompanyVehicleController@update')->name('shipping_company_vehicle.update');
            Route::post('shipping_company_vehicle', 'ShippingCompanyVehicleController@store')->name('shipping_company_vehicle.store');
            Route::delete('shipping_company_vehicle/{id}', 'ShippingCompanyVehicleController@destroy')->name('shipping_company_vehicle.destroy');

            Route::get('client', 'ClientController@index')->name('client.index');
            Route::get('client/all', 'ClientController@all')->name('client.all');
            Route::get('client/{id}', 'ClientController@show')->name('client.show');
            Route::put('client/{id}', 'ClientController@update')->name('client.update');
            Route::post('client', 'ClientController@store')->name('client.store');
            Route::delete('client/{id}', 'ClientController@destroy')->name('client.destroy');

            Route::get('product', 'ProductController@index')->name('product.index');
            Route::get('product/{id}', 'ProductController@show')->name('product.show');
            Route::put('product/{id}', 'ProductController@update')->name('product.update');
            Route::post('product', 'ProductController@store')->name('product.store');
            Route::delete('product/{id}', 'ProductController@destroy')->name('product.destroy');

            Route::get('product_category', 'ProductCategoryController@index')->name('product_category.index');
            Route::get('product_category/all', 'ProductCategoryController@all')->name('product_category.all');
            Route::get('product_category/{id}', 'ProductCategoryController@show')->name('product_category.show');
            Route::put('product_category/{id}', 'ProductCategoryController@update')->name('product_category.update');
            Route::post('product_category', 'ProductCategoryController@store')->name('product_category.store');
            Route::delete('product_category/{id}', 'ProductCategoryController@destroy')->name('product_category.destroy');

            Route::get('user', 'UserController@index')->name('user.index');
            Route::get('user/{id}', 'UserController@show')->name('user.show');
            Route::put('user/{id}', 'UserController@update')->name('user.update');
            Route::post('user', 'UserController@store')->name('user.store');            
            Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');

            Route::get('company', 'CompanyController@index')->name('company.index');            
            Route::get('company/{id}', 'CompanyController@show')->name('company.show');
            Route::put('company/{id}', 'CompanyController@update')->name('company.update');
            Route::post('company/file', 'CompanyController@store_file')->name('company.store.file');
            Route::post('company/image', 'CompanyController@store_image')->name('company.store.image');
            Route::get('company/image', 'CompanyController@show_image')->name('company.show.image');
            Route::delete('company/file', 'CompanyController@destroy_file')->name('company.destoy.file');

            Route::group(['middleware' => ['role:root']], function () {

                Route::post('company', 'CompanyController@store')->name('company.store');
                Route::delete('company/{id}', 'CompanyController@destroy')->name('company.destroy');

            });

        });

        Route::get('cnpj', 'CNPJController@show')->name('cnpj.show');

        Route::get("cep/{cep}", function ($cep) {
            $cep = preg_replace('/\D/', '', $cep);
            return ['data' => cep($cep)->toArray()->result()];
        });

        Route::get('state', function () {
            return ['data' => \App\Models\State::orderBy('name')->get(['id', 'name', 'abbr'])];
        });

        Route::get('city/{id}', function ($id) {
            return ['data' => \App\Models\City::orderBy('name')->where('state_id', '=', $id)->get(['id', 'name'])];
        });

    });

});
