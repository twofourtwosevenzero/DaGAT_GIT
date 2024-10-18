<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['document', 'signatory.office'])->orderBy('Timestamp', 'asc');
        
        if ($request->has('description') && $request->description != '') {
            $query->whereHas('document', function($q) use ($request) {
                $q->where('Description', 'like', '%' . $request->description . '%');
            });
        }

        if ($request->has('action') && $request->action != '') {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->has('office') && $request->office != '') {
            $query->whereHas('signatory.office', function($q) use ($request) {
                $q->where('Office_Name', 'like', '%' . $request->office . '%');
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('Timestamp', [$request->start_date, $request->end_date]);
        }

        $activityLogs = $query->get();
        
        return view('activitylogs.index', compact('activityLogs'));
    }


    public function create()
    {
        return view('activitylogs.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Docu_ID' => 'required',
            'Sign_ID' => 'required',
            'Timestamp' => 'required|date',
        ]);

        ActivityLog::create($validatedData);

        return redirect()->route('activitylogs.index')->with('success', 'Activity log created successfully.');
    }

    public function show(ActivityLog $activityLog)
    {
        return view('activitylogs.show', compact('activityLog'));
    }

    public function edit(ActivityLog $activityLog)
    {
        return view('activitylogs.edit', compact('activityLog'));
    }

    public function update(Request $request, ActivityLog $activityLog)
    {
        $validatedData = $request->validate([
            'Docu_ID' => 'required',
            'Sign_ID' => 'required',
            'Timestamp' => 'required|date',
        ]);

        $activityLog->update($validatedData);

        return redirect()->route('activitylogs.index')->with('success', 'Activity log updated successfully.');
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return redirect()->route('activitylogs.index')->with('success', 'Activity log deleted successfully.');
    }
}
