<?php

namespace App\Http\Controllers;

use App\Models\Signatory;
use Illuminate\Http\Request;

class SignatoryController extends Controller
{
    public function index()
    {
        $signatories = Signatory::all();
        return view('signatories.index', compact('signatories'));
    }

    public function create()
    {
        return view('signatories.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'QRC_ID' => 'required',
            'Office_Pin' => 'required',
            'Status' => 'required|string',
            'Received_Date' => 'required|date',
            'Signed_Date' => 'nullable|date',
        ]);

        Signatory::create($validatedData);

        return redirect()->route('signatories.index')->with('success', 'Signatory created successfully.');
    }

    public function show(Signatory $signatory)
    {
        return view('signatories.show', compact('signatory'));
    }

    public function edit(Signatory $signatory)
    {
        return view('signatories.edit', compact('signatory'));
    }

    public function update(Request $request, Signatory $signatory)
    {
        $validatedData = $request->validate([
            'QRC_ID' => 'required',
            'Office_Pin' => 'required',
            'Status' => 'required|string',
            'Received_Date' => 'required|date',
            'Signed_Date' => 'nullable|date',
        ]);

        $signatory->update($validatedData);

        return redirect()->route('signatories.index')->with('success', 'Signatory updated successfully.');
    }

    public function destroy(Signatory $signatory)
    {
        $signatory->delete();

        return redirect()->route('signatories.index')->with('success', 'Signatory deleted successfully.');
    }
}
