<?php

use App\User;
use Illuminate\Support\Facades\Auth;
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

Route::get('/register', function () {
    // TODO: Remove this function before sending
    return '
<form method="post">
    <input name="name" value="yair" />
    <input name="email" value="yo@yoyoai.com" />
    <input name="password" value="12121"/>
    <input type="submit" />
</form>
';
});
Route::post('/register', function(Request $request) {
    $data = Request::post();
    // TODO: Insecure registration!!! Do not send project without fixing!!!
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);
});

Route::get('/is_logged_in', function() {
    if (Auth::check()) {
        return  ['logged_in' => true];
    }
    return  ['logged_in' => false];
});

Route::get('/login', function () {
    // TODO: Remove this function before sending
    return '
<form method="post">
    <input name="email" value="yo@yoyoai.com" />
    <input name="password" value="12121"/>
    <input type="submit" />
</form>
';
});

Route::post('/login', function(Request $request) {
    $data = Request::post();
    $credentials = ['email' => $data['email'], 'password' => $data['password']];
    if (Auth::attempt($credentials)) {
        return ['success' => true];
    }
    return ['success' => false];
});

Route::get('/todo-list', 'TodoListController@list');