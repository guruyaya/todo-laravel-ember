<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    function register(Request $request) {
        $data = $request->post();
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:5'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ( $validator->fails() ){
            return ['success' => false, 'errors' => $validator->messages()];
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return $this->login($request);
    }

    function is_logged_in() {
        return  ['logged_in' => Auth::check()];
    }

    function login(Request $request) {
        $data = $request->post();
        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        return ['success' => (bool)Auth::attempt($credentials)];
    }

    function logout(Request $request) {
        $success = Auth::logout();
        return ['success' => !Auth::check()]; // if logged out - logout is successful
    }
}
