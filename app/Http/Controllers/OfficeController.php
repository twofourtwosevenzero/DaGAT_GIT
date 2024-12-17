<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Signatory;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class OfficeController extends Controller
{
    /**
     * Display a listing of the offices.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offices = Office::all();
        return view('offices.index', compact('offices'));
    }

    /**
     * Store a newly created office in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pin'  => 'required|string|max:255|unique:offices,Office_Pin',
        ]);

        try {
            Office::create([
                'Office_Name' => $validated['name'],
                'Office_Pin' => $validated['pin'],
            ]);

            return redirect()->route('offices.index')->with('success', 'Office added successfully.');
        } catch (QueryException $e) {
            // Check for duplicate entry error code
            if ($e->getCode() === '23000') {
                return redirect()->back()->withInput()->with('error', 'Duplicate Office Pin detected. Please use a unique pin.');
            }
            return redirect()->back()->with('error', 'An error occurred while adding the office. Please try again later.');
        }
    }

    /**
     * Update the specified office in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id Office ID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $office = Office::findOrFail($id);

        // Validate input. For uniqueness, exclude current office from the unique check
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'Pin'  => 'required|string|max:255|unique:offices,Office_Pin,' . $office->id,
        ]);

        try {
            $office->update([
                'Office_Name' => $validated['name'],
                'Office_Pin' => $validated['Pin'],
            ]);

            return redirect()->route('offices.index')->with('success', 'Office updated successfully.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->back()->withInput()->with('error', 'Duplicate Office Pin detected. Please use a unique pin.');
            }
            return redirect()->back()->with('error', 'An error occurred while updating the office. Please try again later.');
        }
    }

    /**
     * Remove the specified office from storage.
     *
     * @param  int  $id Office ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $office = Office::findOrFail($id);

        // Check if office can be deleted (for example, ensure no signatories are associated)
        $count = Signatory::where('Office_ID', $id)->count();
        if ($count > 0) {
            return redirect()->route('offices.index')->with('error', 'Cannot delete office, it has associated signatories.');
        }

        try {
            $office->delete();
            return redirect()->route('offices.index')->with('success', 'Office deleted successfully.');
        } catch (QueryException $e) {
            // If any database error occurs
            return redirect()->route('offices.index')->with('error', 'An error occurred while deleting the office. Please try again later.');
        }
    }
}
