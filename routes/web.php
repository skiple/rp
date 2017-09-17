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

// contents for api
Route::get('/storage/app/public/images/{type}/{filename}', function ($type, $filename)
{
    $path = storage_path() . '/app/public/images/' . $type . '/' . $filename;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

//to retrieve file
Route::get('public/images/{type}/{filename}', function ($type, $filename)
{	
    $path = storage_path('app/public/images/' . $type . '/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

//Activity Controller Routes
Route::get('/', [
	'uses' => 'ActivityController@viewActivityCatalog',
]);

Route::get('/detail/activity/{id}', [
	'uses' => 'ActivityController@viewDetailActivity',
]);

//Admin Activity Controller Routes
Route::get('/add_activity', [
	'uses' => 'AdminActivityController@viewAddActivity',
]);

Route::post('add_activity', [
	'uses' => 'AdminActivityController@postAddActivity',
	'as' => 'add_activity'
]);

//User Controller Routes
Route::get('logout', [
	'uses' => 'UserController@logout',
]);

Route::post('postSignUp', [
	'uses' => 'UserController@postSignUp',
	'as' => 'sign_up'
]);

Route::post('postSignIn', [
	'uses' => 'UserController@postSignIn',
	'as' => 'sign_in'
]);

Route::get('change_password', [
	'uses' => 'UserController@changePassword',
])->middleware('isLoggedIn');

Route::post('postChangePassword', [
	'uses' => 'UserController@postChangePassword',
	'as' => 'change_password'
])->middleware('isLoggedIn');

//Transaction Controller Routes
Route::get('transactions', [
	'uses' => 'TransactionController@viewTransactions',
]);

Route::post('postCreateTransaction', [
	'uses' => 'TransactionController@postCreateTransaction',
	'as' => 'create_transaction'
]);

Route::get('confirm_payment/{id}', [
	'uses' => 'TransactionController@viewConfirmPayment',
]);

Route::post('postCreatePayment', [
	'uses' => 'TransactionController@postCreatePayment',
	'as' => 'create_payment'
]);

Route::get('detail/transaction/{id}', [
	'uses' => 'TransactionController@viewDetailTransaction',
]);

//Admin Transaction Controller Routes
Route::get('/admin/transactions', [
	'uses' => 'AdminTransactionController@viewTransactions',
]);

Route::get('admin/detail/transaction/{id}', [
	'uses' => 'AdminTransactionController@viewDetailTransaction',
]);

Route::get('admin/accept_payment/{id}', [
	'uses' => 'AdminTransactionController@acceptPayment',
]);

Route::get('admin/reject_payment/{id}', [
	'uses' => 'AdminTransactionController@rejectPayment',
]);