<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/




Auth::routes();

Route::group([ 'middleware' => ['auth', 'role:web,admin'] ], function () {
    Route::get('/', function () {
        return redirect('home');
    });
    Route::get('/home', 'HomeController@index');
    Route::resource('newsCategories', 'NewsCategoryController');
    Route::resource('news', 'NewsController');
    Route::resource('order_sparepart', 'OrderSparepartController');
    Route::resource('order_job', 'OrderJobController');
    Route::resource('commission_sales', 'CommissionSalesController');
    Route::resource('commission_mechanic', 'CommissionMechanicController');
    Route::resource('productBrands', 'ProductBrandController');
    Route::resource('productUnitModels', 'ProductUnitModelController');
    Route::resource('products', 'ProductController');
    Route::resource('users', 'UserController');
    Route::resource('coupons', 'CouponController');
    Route::resource('job_categories', 'JobCategoryController');
    Route::resource('settings', 'SettingController');
});
