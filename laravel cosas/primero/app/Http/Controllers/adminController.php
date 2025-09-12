<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('index', compact('users'));
    }

    public function register(Request $request)
    {
        $user = new User();
        $user->name    = $request->input('name');
        $user->email   = $request->input('user');
        $user->password= bcrypt($request->input('password'));
        $user->save();

        return redirect('/home');
    }   
}
