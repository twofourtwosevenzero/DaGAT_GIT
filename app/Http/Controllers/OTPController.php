<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class OTPController extends Controller
{
    /**
     * Handle OTP generation and sending via email.
     */
    public function loginwithotppost(Request $request)
    {
        // Validate email input
        $request->validate([
            'email' => 'required|email|max:50',
        ]);

        // Check if the user exists in the database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email not found in Database');
        }

        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Update user record with the hashed OTP
        $user->otp = Hash::make($otp);
        $user->save();

        // Attempt to send OTP email
        try {
            Mail::send('emails.loginwithOTPEmail', ['otp' => $otp], function($message) use($request) {
                $message->to($request->email);
                $message->subject('Your Login Credentials: OTP');
            });

            Log::info('OTP sent to email: ' . $request->email);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP to email: ' . $request->email . '. Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }

        return redirect()->back()->with('success', 'OTP has been sent to your email.');
    }

    /**
     * Handle OTP confirmation and login process.
     */
    public function confirmloginwithotppost(Request $request)
    {
        // Validate email and OTP inputs
        $request->validate([
            'email' => 'required|email|max:50',
            'otp' => 'required|numeric',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->otp, $user->otp)) {
            return redirect()->back()->with('error', 'Invalid E-mail or OTP');
        }

        // Clear OTP after successful login
        $user->otp = null;
        $user->save();

        // Log the user in
        Auth::login($user);

        Log::info('User logged in with OTP: ' . $request->email);

        return redirect()->route('home');
    }
}
