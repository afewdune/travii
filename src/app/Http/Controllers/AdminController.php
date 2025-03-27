<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fish;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $fishList = Fish::paginate(10);
        return view('admin.dashboard', compact('fishList'));

        $adminData = [

            'name' => 'travii',
            'role' => 'admin'
        ];
        return response()->json($adminData);
    }
}