<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('user.userdashboard', compact('user'));
    }

    public function documents()
    {
        $documents = auth()->user()->documents;

        return view('userdashboard.userdocuments', compact('documents'));
    }
}
