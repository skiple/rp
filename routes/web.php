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

Route::get('/', [
	'uses' => 'ActivityController@viewActivityCatalog',
]);

Route::get('/add_activity', [
	'uses' => 'AdminActivityController@viewAddActivity',
]);

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