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

Auth::routes();

Route::domain(env('APP_URL'))->middleware('auth')->group(function() {
    Route::get('/', 'User\User\UserController@index');
    Route::get('/users', 'User\User\UserController@index');
    Route::get('/get_users_list', 'User\User\UserController@getUsersList');
    Route::post('/add_user', 'User\User\UserController@addUser');
    Route::post('/find_user', 'User\User\UserController@findUser');
    Route::post('/edit_user', 'User\User\UserController@editUser');
    Route::delete('/delete_user', 'User\User\UserController@deleteUser');

    Route::get('/clients', 'User\Client\ClientController@index');
    Route::get('/get_clients_list', 'User\Client\ClientController@getClientsList');
    Route::post('/add_client', 'User\Client\ClientController@addClient');
    Route::post('/find_client', 'User\Client\ClientController@findClient');
    Route::post('/edit_client', 'User\Client\ClientController@editClient');
    Route::delete('/delete_client', 'User\Client\ClientController@deleteClient');
});


