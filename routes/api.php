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
});