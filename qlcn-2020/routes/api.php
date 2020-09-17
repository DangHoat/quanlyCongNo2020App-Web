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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    //xử lý tài khoản và token
    Route::post('login', 'APIAuthController@login');
    Route::post('logout', 'APIAuthController@logout');
    Route::put('resgiter', 'APIAuthController@resgiter');
    Route::post('refresh', 'APIAuthController@refresh');
    Route::post('me', 'APIAuthController@me');
    Route::post('forgot-password', 'APIAuthController@postForgotPassword');
    Route::patch('changePassword','APIAuthController@changePassword');
    Route::patch('changeRole','APIAuthController@ChangeRole');
    Route::get('all', 'APIAuthController@getAllUsser');

    
    // Xử lý khách hàng
    Route::post('client', 'APIClientController@makeClient');
    Route::patch('client', 'APIClientController@updateClient');
    Route::put('client', 'APIClientController@paySomeMoney');
    Route::delete('client', 'APIClientController@deleteClient');
    Route::get('client', 'APIClientController@getAllClient');
    Route::get('client/pay/{code}', 'APIClientController@pay');
    Route::post('client/getClientByCode', 'APIClientController@getClientByCode');
    Route::get('client/some/{start}/{end}', 'APIClientController@getSomeClient');
    //Xử lý hóa đơn
    Route::get('bill',"APIBillController@getAllBills");
    Route::post('bill',"APIBillController@CreateBill");
     Route::put('bill',"APIBillController@updateBill");
    Route::get('bill/some/{start}/{end}',"APIBillController@getSomeBill");
    Route::post('bill/client',"APIBillController@getBillOfClient");
    Route::delete('bill/client/{id}',"APIBillController@deleteBill");
    Route::get('bill/client/some/{code}/{start}/{end}',"APIBillController@getSomeBillOfClient");
    
});