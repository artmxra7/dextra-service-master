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

Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
Route::post('/register', 'AuthController@register');
Route::post('/activate', 'AuthController@activate'); // Activate account
Route::post('/forgot', 'AuthController@forgot'); // Request for reseting password
Route::post('/reset', 'AuthController@reset'); // Request for reseting password

Route::post('/jobs/status/{status}', 'JobController@jobListByStatus');

Route::group([ 'middleware' => 'auth:api' ], function () {
    Route::post('/location', 'AuthController@storeLocation');
    Route::post('/fcm-token/{id}', 'AuthController@store_fcm_token');

    // Sales & Member
    Route::resource('/news', 'NewsController', [
        'only' => [
            'index', 'show'
        ]
    ]);

    // Sales & Member
    Route::resource('/products', 'ProductController', [
        'only' => [
            'index', 'show'
        ]
    ]);
    Route::get('/products/unit_model/{unit_model}', 'ProductController@getListUnitModel');

    // Sales & Member
    Route::resource('/product_unit_models', 'ProductUnitModelController', [
        'only' => [
            'index'
        ]
    ]);
    Route::get('/product_unit_models/name', 'ProductUnitModelController@index_name');

    //  Mechanic & Sales
    Route::resource('/commissions', 'CommissionController', [
        'only' => [
            'index',
            'show',
            'store'
        ]
    ]);

    Route::resource('/jobs', 'JobController', [
        'only' => [
            'index',
            'show',
            'store',
            'update'
        ]
    ]);
    // Route::post('/jobs/status/{status}', 'JobController@jobListByStatus');
    Route::get('/jobs/days/schedule/{status}', 'JobDayController@jobDayScheduleList');
    Route::get('/jobs/days/check-wip', 'JobDayController@checkMechanicIsWIP');
    Route::post('/jobs/days/{id}/start', 'JobDayController@startWorking');
    Route::post('/jobs/days/{id}/finish', 'JobDayController@finishWorking');
    Route::post('/jobs/days/mass', 'JobDayController@storeMass');
    Route::resource('/jobs/days', 'JobDayController', [
        'only' => [
            'show',
            'store',
            'update',
            'destroy',
        ]
    ]);
    Route::resource('/jobs-days-photos', 'JobDayPhotoController', [
      'only' => [
        'index',
        'show',
        'store',
        'update',
      ]
    ]);
    Route::get('/jobs/days/get/{id}', 'JobDayController@getJobDay');

    Route::resource('/job_categories', 'JobCategoryController', [
        'only' => [
            'index'
        ]
    ]);

    Route::resource('/job_mechanics', 'JobMechanicController', [
        'only' => [
            'store',
            'update'
        ]
    ]);

    Route::post('/mechanics/all', 'MechanicController@getAll');
    Route::post('/mechanics/by-radius', 'MechanicController@getByRadius');

    Route::resource('/orders', 'OrderController', [
        'only' => [
            'index',
            'show',
            'store',
            'update'
        ]
    ]);

    Route::resource('/payments', 'PaymentController', [
        'only' => [
            'index',
            'show',
            'store',
            'update'
        ]
    ]);

    // Admin
    Route::resource('/purchases', 'PurchaseController', [
        'only' => [
            'store'
        ]
    ]);

    Route::resource('/settings', 'SettingController', [
        'only' => [
            'index',
            'show',
            'store',
            'update'
        ]
    ]);

    Route::get('/withdraws/status/{status}', 'WithdrawController@withdrawListByStatus');
    Route::resource('/withdraws', 'WithdrawController', [
        'only' => [
            'index',
            'show',
            'store',
            'update'
        ]
    ]);

    Route::resource('/quotations', 'QuotationController', [
        'only' => [
            'index', 'show', 'store'
        ]
    ]);

    // Sales Routes
    Route::group(['prefix' => 'sales'], function () {
        Route::resource('/customers', 'CustomerController', [
            'only' => [
                'index',
                'show',
                'store',
                'update',
            ]
        ]);
    });

    // Member Routes
    Route::group(['prefix' => 'member'], function () {
    });

    // Mechanic Routes
    Route::group(['prefix' => 'mechanic'], function () {
    });

    // Admin Routes
    Route::group(['prefix' => 'admin'], function () {
    });
});
