<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'position' => ['required', 'string', 'max:255'], // Ensure correct validation rules
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the user with validated data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position, // Use the correct field from request
            'password' => Hash::make($request->password),
        ]);

        // Dispatch the Registered event
        event(new Registered($user));

        // Automatically log in the user after registration
        Auth::login($user);

        // Redirect to the dashboard or any desired route
        return redirect()->route('dashboard');
    }
}
