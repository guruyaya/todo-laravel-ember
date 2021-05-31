<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function register(Request $request) {
        $data = $request->post();
        // TODO: Insecure registration!!! Do not send project without fixing!!!
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    function is_logged_in() {
        return  ['logged_in' => Auth::check()];
    }

    function login(Request $request) {
        $data = $request->post();
        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        return ['success' => bool( Auth::attempt($credentials ))];
    }

    function logout(Request $request) {
        $success = Auth::logout();
        return ['success' => !Auth::check()]; // if logged out - logout is successful
    }
}
