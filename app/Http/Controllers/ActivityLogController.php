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

        // Filter by description
        if ($request->filled('description')) {
            $query->whereHas('document', function($q) use ($request) {
                $q->where('Description', 'like', '%' . $request->description . '%');
            });
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by user
        if ($request->filled('user')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end = Carbon::parse($request->end_date)->endOfDay();
                $query->whereBetween('Timestamp', [$start, $end]);
            } catch (\Exception $e) {
                // If parsing fails, you can handle the error or fallback here
            }
        }

        $activityLogs = $query->get();

        return view('activitylogs.index', compact('activityLogs'))
            ->with([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'action' => $request->action,
                'user' => $request->user
            ]);
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
