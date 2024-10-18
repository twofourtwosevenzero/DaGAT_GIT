<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position; // Ensure Position model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // UserController.php
    public function index()
    {
        $users = User::with('position')->get();
        return view('userManagement.UserManagement', compact('users'));
    }

    public function showUserManagement()
    {
        $users = User::with('position')->get();
        return view('usermanagement.usermanagement', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'position_id' => 'required|exists:positions,id',
            'usertype' => 'required|string|max:10',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'position_id' => $request->position_id,
            'usertype' => $request->usertype,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.showUserManagement')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('UserManagement.EditUser', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => '|string|max:50',
        ]);

        $user->update($request->only(['name', 'email', 'position_id', 'usertype']));

        return redirect()->route('user.showUserManagement')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.showUserManagement')->with('success', 'User deleted successfully');
    }
}
