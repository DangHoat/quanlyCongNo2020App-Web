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

Route::pattern('slug', '[a-z0-9- _]+');

//Route for anyone
Route::group(['namespace'=>'Admin'], function () {
    # Error pages should be shown without requiring login
    Route::get('404', function () {
        return view('admin/404');
    });
    Route::get('500', function () {
        return view('admin/500');
    });
    # All basic routes defined here
    Route::get('/', function () {
        return redirect('/signin');
    });
    Route::get('signin', 'AuthController@getSignin')->name('signin');
    Route::post('signin', 'AuthController@postSignin')->name('postSignin');
    Route::post('forgot-password', 'AuthController@postForgotPassword')->name('forgot-password');
    Route::post('change-pwd/{active_code}/{user_id}', 'AuthController@changePassword')->name('change-password');

    # Forgot Password Confirmation
    Route::get('reset/{email}/{resetCode}', 'AuthController@getForgotPasswordConfirm');
    Route::post('reset/{email}/{resetCode}', 'AuthController@postForgotPasswordConfirm');

    # Logout
    Route::get('logout', 'AuthController@getLogout')->name('logout');

    # Account Activation
    Route::get('activate/{userId}/{activationCode}', 'AuthController@getActivate')->name('activate');
});

Route::group(['prefix' => 'admin', 'middleware' => 'login', 'as' => 'admin.'], function () {
    # Dashboard
    Route::get('/', 'CoverController@showHome')->name('dashboard');
});

Route::group([ 'middleware' => 'login'], function () {
    #Export Excel
    Route::get('export_bill/{bill}','ExcelController@exportBill')->name('export_bill');
    Route::get('export_client','ExcelController@exportClient')->name('export_client');
});

Route::group(['prefix' => 'admin','namespace'=>'Admin', 'middleware' => 'login', 'as' => 'admin.'], function () {

    # User Management
    Route::group([ 'prefix' => 'users'], function () {
        Route::get('profile', 'UsersController@show_edit_profile')->name('users.show_edit_profile');
        Route::put('profile', 'UsersController@update_profile')->name('users.update_profile');
        Route::post('passwordreset', 'UsersController@passwordreset')->name('passwordreset');
    });

    # Client Management
    Route::group([ 'prefix' => 'clients'], function () {
        Route::get('data', 'ClientsController@data')->name('clients.data');
    });
    Route::resource('clients', 'ClientsController', ['only' => ['index']]);

    #Bill Management
    Route::group([ 'prefix' => 'bills'], function () {
        Route::get('data/{client}', 'BillsController@data')->name('bills.data');
    });
    Route::resource('bills', 'BillsController', ['only' => ['show']]);

    Route::group(['middleware' => 'admin'],function () {
        # User Management
        Route::group([ 'prefix' => 'users'], function () {
            Route::get('data', 'UsersController@data')->name('users.data');
            Route::get('{user}/confirm-delete', 'UsersController@getModalDelete')->name('users.confirm-delete');
        });
        Route::resource('users', 'UsersController', ['except' => ['show']]);

        # Client Management
        Route::group([ 'prefix' => 'clients'], function () {
            Route::get('{client}/confirm-delete', 'ClientsController@getModalDelete')->name('clients.confirm-delete');
        });
        Route::resource('clients', 'ClientsController', ['except' => ['show','index']]);

        #Bill Management
        Route::group([ 'prefix' => 'bills'], function () {
            Route::get('create/{client}', 'BillsController@create')->name('bills.create');
            Route::get('delete/{bill}', 'BillsController@destroy')->name('bills.destroy');
        });
        Route::resource('bills', 'BillsController', ['only' => ['store']]);
    });
});
