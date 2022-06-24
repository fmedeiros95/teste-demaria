<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Redirect to panel or auth if not logged in
Route::get('/', function() {
    $isLoggedIn = Auth::check();
    return redirect($isLoggedIn ? '/panel/dashboard' : 'auth');
});

// Authentication routes
Route::group(['prefix' => 'auth'], function() {
    Route::get('/', 'AuthController@index');
    Route::post('/', 'AuthController@authenticate');

    Route::get('logout', 'AuthController@logout');
});

// Panel routes
Route::group(['prefix' => 'panel'], function() {
    // Redirect to dashboard
    Route::get('/', function() { redirect('/panel/dashboard'); });

    Route::get('/dashboard', 'DashboardController@index');
	Route::get('/dashboard/logs', 'DashboardController@logs');

    // Products routes
    Route::group(['prefix' => 'products'], function() {
		Route::get('autocomplete', 'ProductsController@autocomplete');

        Route::get('/', 'ProductsController@index');
        Route::post('/', 'ProductsController@store');
        Route::get('/{id}', 'ProductsController@show');
        Route::put('/{id}', 'ProductsController@update');
        Route::delete('/{id}', 'ProductsController@destroy');
    });

    // Sales routes
    Route::group(['prefix' => 'sales'], function() {
        Route::get('/', 'SalesController@index');
        Route::post('/', 'SalesController@store');
        Route::get('/{id}', 'SalesController@show');
        Route::put('/{id}', 'SalesController@update');
        Route::delete('/{id}', 'SalesController@destroy');
    });

    // Customers routes
    Route::group(['prefix' => 'customers'], function() {
		Route::get('/autocomplete', 'CustomersController@autocomplete');

		Route::get('/', 'CustomersController@index');
        Route::post('/', 'CustomersController@store');
        Route::get('/{id}', 'CustomersController@show');
        Route::put('/{id}', 'CustomersController@update');
        Route::delete('/{id}', 'CustomersController@destroy');
    });

	// Users routes
	Route::group(['prefix' => 'users'], function() {
		Route::get('/', 'UsersController@index');
		Route::post('/', 'UsersController@store');
		Route::get('/{id}', 'UsersController@show');
		Route::put('/{id}', 'UsersController@update');
		Route::delete('/{id}', 'UsersController@destroy');
	});

	// Reports routes
	Route::group(['prefix' => 'reports'], function() {
		Route::get('/sellers', 'ReportsController@sellers');
		Route::get('/products', 'ReportsController@products');
	});
});
