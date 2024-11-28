<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position;
use App\Models\Privilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['position', 'privilege'])->get(); // Include privilege relationship
        $positions = Position::all();
        $privileges = DB::table('privileges')->select('id', 'Privilege_Level')->get(); // Fetch Privileges
        return view('usermanagement.UserManagement', compact('users', 'positions', 'privileges'));
    }
    
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'position_id' => 'required|exists:positions,id',
            'password' => 'required|string|min:8',
            'PRI_ID' => 'nullable|string|max:50', // Validate privileges
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'position_id' => $request->position_id,
            'password' => Hash::make($request->password),
            'PRI_ID' => $request->PRI_ID, // Save privileges
        ]);
    
        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users,email,' . $id,
            'position_id' => 'required|exists:positions,id',
            'PRI_ID' => 'nullable|string|max:50', // Validate privileges
        ]);
    
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'position_id', 'PRI_ID'])); // Update privileges
    
        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
