<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    protected $redirectTo = 'admin/dashboard'; 

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the email and password inputs
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate the user with the provided credentials
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Generate an OTP
            $otp = rand(100000, 999999);

            // Store the hashed OTP in the user's record
            $user = Auth::user();
            $user->otp = Hash::make($otp);
            $user->save();

            // Optionally, send the OTP via email, SMS, or other methods
            // For example: Mail::to($user->email)->send(new OTPMail($otp));

            // Store the user ID in the session for OTP confirmation
            Session::put('otp_user_id', $user->id);

            // Redirect to the OTP confirmation page
            return redirect()->route('confirm.login.with.otp');
        }

        // Handle specific error messages for email and password
        $errors = [];

        // Check if the email exists in the database
        if (!Auth::validate(['email' => $request->email])) {
            $errors['email'] = 'The email address is not registered.';
        } else {
            // If the email exists, the password must be incorrect
            $errors['password'] = 'The provided password is incorrect.';
        }

        // Redirect back with errors and retain the email input
        return back()->withErrors($errors)->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
