<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['document', 'signatory.office', 'user'])->orderBy('Timestamp', 'desc');
                        
        if ($request->has('description') && $request->description != '') {
            $query->whereHas('document', function($q) use ($request) {
                $q->where('Description', 'like', '%' . $request->description . '%');
            });
        }

        if ($request->has('action') && $request->action != '') {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->has('user') && $request->user != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
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
            'Docu_ID' => 'required|exists:documents,id',
            'Sign_ID' => 'nullable|exists:signatories,id',
            'Timestamp' => 'required|date',
            'action' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        // Determine if the action is performed by a user or an office
        if ($validatedData['action'] == 'Deleted') {
            $userId = Auth::id();
            $signatoryId = null;
        } elseif ($validatedData['action'] == 'Revision Requested') {
            // Assuming you have a way to get the office making the request
            // For example, via a form field 'office_id'
            $officeId = $request->input('office_id'); // Ensure this field is provided and validated
            $userId = null;
            $signatoryId = null; // Or set accordingly if linked
        } else {
            $userId = Auth::id();
            $signatoryId = $request->input('Sign_ID');
        }

        ActivityLog::create([
            'Docu_ID' => $validatedData['Docu_ID'],
            'Sign_ID' => $signatoryId,
            'action' => $validatedData['action'],
            'Timestamp' => $validatedData['Timestamp'],
            'reason' => $validatedData['reason'],
            'user_id' => $userId,
        ]);

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
            'Docu_ID' => 'required|exists:documents,id',
            'Sign_ID' => 'nullable|exists:signatories,id',
            'Timestamp' => 'required|date',
            'action' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        // Update with authenticated user
        $activityLog->update([
            'Docu_ID' => $validatedData['Docu_ID'],
            'Sign_ID' => $validatedData['Sign_ID'],
            'action' => $validatedData['action'],
            'Timestamp' => $validatedData['Timestamp'],
            'reason' => $validatedData['reason'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('activitylogs.index')->with('success', 'Activity log updated successfully.');
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return redirect()->route('activitylogs.index')->with('success', 'Activity log deleted successfully.');
    }
}
