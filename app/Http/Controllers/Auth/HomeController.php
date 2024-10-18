<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->PRI_ID == '1') {
            return view('admin.dashboard');
        } else {
            return view('userdashboard.userdashboard');
        }
    }
}
