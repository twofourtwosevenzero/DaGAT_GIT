<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\ApprovedFile;
use App\Models\Office;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', compact('documentStatuses', 'approvedFiles', 'recentLogs', 'officeMetrics', 'monthlyProcessedDocumentsData', 'topPerformingOffice', 'months'));
    }
}
