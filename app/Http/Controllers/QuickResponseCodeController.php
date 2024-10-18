<?php

namespace App\Http\Controllers;

use App\Models\QuickResponseCode;
use Illuminate\Http\Request;

class QuickResponseCodeController extends Controller
{
    public function index()
    {
        $qrcodes = QuickResponseCode::all();
        return view('quick_response_codes.index', compact('qrcodes'));
    }

    public function create()
    {
        return view('quick_response_codes.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Docu_ID' => 'required',
            'QR_Image' => 'required|string',
            'Date_Created' => 'required|date',
            'Usage_Count' => 'required|integer',
        ]);

        QuickResponseCode::create($validatedData);

        return redirect()->route('quick-response-codes.index')->with('success', 'QR Code created successfully.');
    }

    public function show(QuickResponseCode $quickResponseCode)
    {
        return view('quick_response_codes.show', compact('quickResponseCode'));
    }

    public function edit(QuickResponseCode $quickResponseCode)
    {
        return view('quick_response_codes.edit', compact('quickResponseCode'));
    }

    public function update(Request $request, QuickResponseCode $quickResponseCode)
    {
        $validatedData = $request->validate([
            'Docu_ID' => 'required',
            'QR_Image' => 'required|string',
            'Date_Created' => 'required|date',
            'Usage_Count' => 'required|integer',
        ]);

        $quickResponseCode->update($validatedData);

        return redirect()->route('quick-response-codes.index')->with('success', 'QR Code updated successfully.');
    }

    public function destroy(QuickResponseCode $quickResponseCode)
    {
        $quickResponseCode->delete();

        return redirect()->route('quick-response-codes.index')->with('success', 'QR Code deleted successfully.');
    }
}
