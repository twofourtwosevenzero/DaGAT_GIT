<?php

namespace App\Http\Controllers;

use App\Models\Privilege;
use Illuminate\Http\Request;

class PrivilegeController extends Controller
{
    public function index()
    {
        $privileges = Privilege::all();
        return view('privileges.index', compact('privileges'));
    }

    public function create()
    {
        return view('privileges.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Privilege_Level' => 'required|string',
        ]);

        Privilege::create($validatedData);

        return redirect()->route('privileges.index')->with('success', 'Privilege created successfully.');
    }

    public function show(Privilege $privilege)
    {
        return view('privileges.show', compact('privilege'));
    }

    public function edit(Privilege $privilege)
    {
        return view('privileges.edit', compact('privilege'));
    }

    public function update(Request $request, Privilege $privilege)
    {
        $validatedData = $request->validate([
            'Privilege_Level' => 'required|string',
        ]);

        $privilege->update($validatedData);

        return redirect()->route('privileges.index')->with('success', 'Privilege updated successfully.');
    }

    public function destroy(Privilege $privilege)
    {
        $privilege->delete();

        return redirect()->route('privileges.index')->with('success', 'Privilege deleted successfully.');
    }
}
