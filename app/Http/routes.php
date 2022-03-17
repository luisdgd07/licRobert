<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => ['auth']], function()
{

	Route::get('/', 'FolderController@listar');
	Route::get('folders','FolderController@getFolders');
	Route::get('list-file','FileController@listar');
	Route::post('create-folder','FolderController@createFolder');
	Route::post('/share-folder','FolderController@shareFolder');
	Route::any('/upload', 'FileController@upload');
	Route::any('/download', 'FileController@download');
	Route::any('/delete', 'FileController@getDelete');
	Route::any('/users', 'UserController@show');
	Route::any('/list-user', 'UserController@listar');
	Route::any('/delete-user', 'UserController@getDelete');
});

Route::get('auth/register', 'Auth\AuthController@getRegister')->middleware('admin');
Route::post('auth/register', 'Auth\AuthController@postRegister')->middleware('admin');	

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);	

	




