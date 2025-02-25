<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the currently logged-in user
        return view('users', compact('user')); // Pass user data to the view
    }

    public function index2()
    {
        $users = User::all();
        return view('users_data', compact('users'));
    }
}
