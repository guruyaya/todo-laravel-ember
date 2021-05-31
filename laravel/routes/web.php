<?php

use App\User;
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

Route::get('/', function () {
    return view('welcome');
});

// TODO: move to it's own controller
Route::get('/is-logged-in', 'UserController@is_logged_in');
Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');

Route::get('/todo-list', 'TodoListController@list');
Route::post('/set-completed', 'TodoListController@set_completed');
Route::post('/new-item', 'TodoListController@new_item');