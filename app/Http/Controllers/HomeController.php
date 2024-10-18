<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\Office;
use App\Models\ActivityLog;
use App\Models\ApprovedFile;
use App\Models\Status;
use App\Models\Signatory; // Added Signatory model
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->PRI_ID == '1') {
            // Define the statuses you want to display
            $desiredStatuses = ['Pending', 'Approved'];

            // Fetch the statuses with document counts, including only the desired statuses
            $documentStatuses = Status::whereIn('Status_Name', $desiredStatuses)
                ->withCount(['documents'])
                ->get()
                ->keyBy('Status_Name');

            // Ensure all desired statuses are present, even if the count is 0
            foreach ($desiredStatuses as $statusName) {
                if (!$documentStatuses->has($statusName)) {
                    $documentStatuses->put($statusName, (object)[
                        'Status_Name' => $statusName,
                        'documents_count' => 0
                    ]);
                }
            }

            // Fetch the number of approved files as archived
            $approvedFiles = ApprovedFile::count();

            $recentLogs = ActivityLog::with(['document', 'signatory.office'])
                ->where('action', '!=', 'Fully Approved')
                ->orderBy('Timestamp', 'desc')
                ->limit(5)  // Fetch the top 5 logs
                ->get()
                ->map(function ($log) {
                    $log->Timestamp = Carbon::parse($log->Timestamp); // Parse Timestamp as Carbon instance
                    return $log;
                });

            // Fetch the monthly processed documents
            $monthlyProcessedDocuments = ActivityLog::select(
                DB::raw('MONTH(Timestamp) as month'), 
                DB::raw('count(*) as count')
            )
            ->groupBy(DB::raw('MONTH(Timestamp)'))
            ->orderBy(DB::raw('MONTH(Timestamp)'))
            ->get();

            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            $monthlyProcessedDocumentsData = array_fill(1, 12, 0); // Ensure all months are represented
            foreach ($monthlyProcessedDocuments as $data) {
                $monthlyProcessedDocumentsData[$data->month] = $data->count;
            }

            // Compute overall average processing time across all signatories
            $averageProcessingTime = Signatory::whereNotNull('Received_Date')
                ->whereNotNull('Signed_Date')
                ->get()
                ->map(function ($signatory) {
                    $received = Carbon::parse($signatory->Received_Date);
                    $signed = Carbon::parse($signatory->Signed_Date);
                    return $received->diffInDays($signed);
                })
                ->average();

            $averageProcessingTime = $averageProcessingTime ? round($averageProcessingTime, 2) : 0;

            // Documents approved this month
            $approvedThisMonth = Document::where('Status_ID', 3) // Assuming 3 is 'Approved'
                ->whereMonth('Date_Approved', Carbon::now()->month)
                ->count();

            // Active signatories (offices that have approved documents in the last 6 months)
            $activeSignatories = Signatory::whereNotNull('Signed_Date')
                ->where('Signed_Date', '>=', Carbon::now()->subMonths(6))
                ->distinct('Office_ID')
                ->count('Office_ID');

            return view('admin.dashboard', compact(
                'documentStatuses', 
                'approvedFiles', 
                'recentLogs', 
                'monthlyProcessedDocumentsData', 
                'months',
                'averageProcessingTime',
                'approvedThisMonth',
                'activeSignatories'
            ));
        } else {
            return view('userdashboard.userdashboard');
        }
    }
}
