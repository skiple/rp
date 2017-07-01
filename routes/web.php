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

Route::get('/', [
	'uses' => 'ActivityController@viewActivityCatalog',
]);

//Activity Controller Routes
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