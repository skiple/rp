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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace'=>'Api'],function (){
    Route::get('/activity', 'ActivityModule@getAllActivity');
    Route::get('/activity/{id}', 'ActivityModule@getActivity');
    Route::post('/signin', 'UserModule@signIn');
    Route::post('/signup', 'UserModule@signUp');
    Route::post('/forgot_password', 'UserModule@forgotPassword');
    Route::get('/reset_password/{token}', 'UserModule@resetPassword');
});

Route::group(['namespace'=>'Api','middleware'=>['auth:api']], function() {
	// Transaction module routes
    Route::get('/transaction', 'TransactionModule@getAllTransactions');
    Route::get('/transaction/{id}', 'TransactionModule@getTransaction');
    Route::post('/transaction', 'TransactionModule@createTransaction');
    Route::get('/transaction/payment/{id}', 'TransactionModule@getPayment');
    Route::post('/transaction/payment', 'TransactionModule@createPayment');

    // user module routes
	Route::get('/logout', 'UserModule@signOut');
    Route::post('/change_password', 'UserModule@changePassword');
});

Route::group(['namespace'=>'Api','middleware'=>['auth:api','check_admin']], function() {
    // Admin activity module routes
    Route::get('/admin/activity', 'AdminActivityModule@getAddActivity');
    Route::post('/admin/activity', 'AdminActivityModule@createActivity');

    // Admin transaction module routes
    Route::get('/admin/transaction', 'AdminTransactionModule@getAllTransactions');
    Route::get('/admin/transaction/{id}', 'AdminTransactionModule@getTransaction');
    Route::post('/admin/transaction/payment/accept/{id}', 'AdminTransactionModule@acceptPayment');
    Route::post('/admin/transaction/payment/reject/{id}', 'AdminTransactionModule@rejectPayment');
});