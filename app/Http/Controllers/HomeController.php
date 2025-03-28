<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }

    public function getUser()
    {
        return response()->json(Auth::user());
    }
}
