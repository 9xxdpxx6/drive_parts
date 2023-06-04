<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'brands'], function () {
        Route::get('/', App\Http\Controllers\Brand\IndexController::class);
        Route::post('/', \App\Http\Controllers\Brand\StoreController::class);
        Route::get('/{brand}', \App\Http\Controllers\Brand\ShowController::class);
        Route::patch('/{brand}', \App\Http\Controllers\Brand\UpdateController::class);
        Route::delete('/{brand}', \App\Http\Controllers\Brand\DestroyController::class);
    });

    Route::group(['prefix' => 'cars'], function () {
        Route::get('/', App\Http\Controllers\Car\IndexController::class);
        Route::post('/', \App\Http\Controllers\Car\StoreController::class);
        Route::get('/{car}', \App\Http\Controllers\Car\ShowController::class);
        Route::patch('/{car}', \App\Http\Controllers\Car\UpdateController::class);
        Route::delete('/{car}', \App\Http\Controllers\Car\DestroyController::class);
    });

    Route::group(['prefix' => 'car_models'], function () {
        Route::get('/', App\Http\Controllers\CarModel\IndexController::class);
        Route::post('/', \App\Http\Controllers\CarModel\StoreController::class);
        Route::get('/{carModel}', \App\Http\Controllers\CarModel\ShowController::class);
        Route::patch('/{carModel}', \App\Http\Controllers\CarModel\UpdateController::class);
        Route::delete('/{carModel}', \App\Http\Controllers\CarModel\DestroyController::class);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', App\Http\Controllers\Category\IndexController::class);
        Route::post('/', \App\Http\Controllers\Category\StoreController::class);
        Route::get('/{category}', \App\Http\Controllers\Category\ShowController::class);
        Route::patch('/{category}', \App\Http\Controllers\Category\UpdateController::class);
        Route::delete('/{category}', \App\Http\Controllers\Category\DestroyController::class);
    });

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/filters', \App\Http\Controllers\Customer\FilterListController::class);
        Route::get('/{customer}', \App\Http\Controllers\Customer\ShowController::class);
        Route::get('/', App\Http\Controllers\Customer\IndexController::class);
        Route::post('/', \App\Http\Controllers\Customer\StoreController::class);
        Route::patch('/{customer}', \App\Http\Controllers\Customer\UpdateController::class);
        Route::delete('/{customer}', \App\Http\Controllers\Customer\DestroyController::class);
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::get('/', App\Http\Controllers\Group\IndexController::class);
        Route::post('/', \App\Http\Controllers\Group\StoreController::class);
        Route::get('/{group}', \App\Http\Controllers\Group\ShowController::class);
        Route::patch('/{group}', \App\Http\Controllers\Group\UpdateController::class);
        Route::delete('/{group}', \App\Http\Controllers\Group\DestroyController::class);
    });

    Route::group(['prefix' => 'labels'], function () {
        Route::get('/', App\Http\Controllers\Label\IndexController::class);
        Route::post('/', \App\Http\Controllers\Label\StoreController::class);
        Route::get('/{label}', \App\Http\Controllers\Label\ShowController::class);
        Route::patch('/{label}', \App\Http\Controllers\Label\UpdateController::class);
        Route::delete('/{label}', \App\Http\Controllers\Label\DestroyController::class);
    });

    Route::group(['prefix' => 'order_statuses'], function () {
        Route::get('/', App\Http\Controllers\OrderStatus\IndexController::class);
        Route::post('/', \App\Http\Controllers\OrderStatus\StoreController::class);
        Route::get('/{orderStatus}', \App\Http\Controllers\OrderStatus\ShowController::class);
        Route::patch('/{orderStatus}', \App\Http\Controllers\OrderStatus\UpdateController::class);
        Route::delete('/{orderStatus}', \App\Http\Controllers\OrderStatus\DestroyController::class);
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/filters', \App\Http\Controllers\Order\FilterListController::class);
        Route::get('/{order}', \App\Http\Controllers\Order\ShowController::class);
        Route::get('/', App\Http\Controllers\Order\IndexController::class);
        Route::post('/', \App\Http\Controllers\Order\StoreController::class);
        Route::patch('/{order}', \App\Http\Controllers\Order\UpdateController::class);
        Route::delete('/{order}', \App\Http\Controllers\Order\DestroyController::class);

        Route::group(['prefix' => 'details'], function () {
            Route::get('/deliveries', App\Http\Controllers\Order\Delivery\IndexController::class);
            Route::get('/payment_methods', App\Http\Controllers\Order\PaymentMethod\IndexController::class);
        });

    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/filters', \App\Http\Controllers\Product\FilterListController::class);
        Route::get('/{product}', \App\Http\Controllers\Product\ShowController::class);
        Route::get('/', App\Http\Controllers\Product\IndexController::class);
        Route::post('/', \App\Http\Controllers\Product\StoreController::class);
        Route::patch('/{product}', \App\Http\Controllers\Product\UpdateController::class);
        Route::delete('/{product}', \App\Http\Controllers\Product\DestroyController::class);
    });

    Route::group(['prefix' => 'providers'], function () {
        Route::get('/', App\Http\Controllers\Provider\IndexController::class);
        Route::post('/', \App\Http\Controllers\Provider\StoreController::class);
        Route::get('/{provider}', \App\Http\Controllers\Provider\ShowController::class);
        Route::patch('/{provider}', \App\Http\Controllers\Provider\UpdateController::class);
        Route::delete('/{provider}', \App\Http\Controllers\Provider\DestroyController::class);
    });

});


//-------------
// Client below
//-------------
//Route::post('/products', \App\Http\Controllers\API\Product\IndexController::class);
//Route::get('/products/filters', \App\Http\Controllers\API\Product\FilterListController::class);
//Route::get('/products/{product}', \App\Http\Controllers\API\Product\ShowController::class);
//Route::post('/orders', \App\Http\Controllers\API\Order\StoreController::class);
