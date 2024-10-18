<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::all();
        return view('offices.index', compact('offices'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:50',
        'pin' => 'required|string|max:10',
    ]);

    $office = new Office;
    $office->Office_Name = $request->name;
    $office->Office_Pin = $request->pin;
    $office->save();

    return redirect()->route('offices.index')->with('success', 'Office added successfully');
}

    public function update(Request $request, $id)
    {
        // Check if the request method is PUT
        if ($request->isMethod('put')) {
            $request->validate([
                'name' => 'required|string|max:50',
                'Pin' => 'required|string|max:10',
            ]);

            $office = Office::findOrFail($id);
            $office->Office_Name = $request->input('name');
            $office->Office_Pin = $request->input('Pin');
            $office->save();

            return redirect()->route('offices.index')->with('success', 'Office updated successfully');
        }

        // If not a PUT request, return an error
        return back()->with('error', 'Invalid method for updating office. Please use PUT method.');
    }

    // Add a new method to handle GET requests for the edit form
    public function edit($id)
    {
        $office = Office::findOrFail($id);
        return view('office.edit', compact('office'));
    }
}