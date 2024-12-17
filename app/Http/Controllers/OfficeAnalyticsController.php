<?php

namespace App\Http\Controllers;

use App\Models\Signatory;
use App\Models\Office;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class OfficeAnalyticsController extends Controller
{

        // Define the abbreviations for the offices
    protected $officeAbbreviations = [
        'Office of the President' => 'OPR',
        'Office of the Vice President for Academic Affairs' => 'OVPAA',
        'Office of the Vice President for Administration' => 'OVPAD',
        'Office of the Vice President for Planning and Quality Assurance' => 'OVPQA',
        'Office of the Vice President for Research, Development and Extension' => 'OVPRDE',
        'Office of the Secretary of the University and the University Records Office' => 'OSURO',
        'Office of Legal Affairs' => 'OLA',
        'International Affairs Division' => 'IAD',
        'Public Affairs Division' => 'PAD',
        'Office of Advance Studies' => 'OAS',
        'Human Resource Management Division' => 'HRMD',
        'Administrative Services Division' => 'ASD',
        'Physical Development Division' => 'PDD',
        'Gender and Development Office' => 'GaD',
        'Bids & Awards Committee' => 'BAC',
        'Office of Student Affairs and Services' => 'OSAS',
        'Office of the University Registrar' => 'OUR',
        'University Assessment and Guidance Center' => 'UAGC',
        'University Learning Resource Center' => 'ULRC',
        'Resource Management Division' => 'RMD',
        'Health Services Division' => 'HSD',
        'University Finance Division' => 'UFD',
        'Research, Development and Extension' => 'RDE',
        'College of Applied Economics' => 'CAEc',
        'College of Arts and Sciences' => 'CAS',
        'College of Business Administration' => 'CBA',
        'College of Education' => 'CEd',
        'College of Engineering' => 'COE',
        'College of Information and Computing' => 'CIC',
        'College of Technology' => 'CT',
        'College of Applied Economics LC' => 'CAEc LC',
        'College of Arts and Sciences LC' => 'CAS LC',
        'College of Business Administration LC' => 'CBA LC',
        'College of Education LC' => 'CEd LC',
        'College of Engineering LC' => 'COE LC',
        'College of Information and Computing LC' => 'CIC LC',
        'College of Technology LC' => 'CT LC',
    ];

    public function index(Request $request)
    {
        // Parse the date filters if provided
        $start_date = $request->has('start_date') ? Carbon::parse($request->start_date)->startOfDay() : null;
        $end_date = $request->has('end_date') ? Carbon::parse($request->end_date)->endOfDay() : null;

        // Base query for signatories that have both Received_Date and Signed_Date
        $signatoriesQuery = Signatory::query()
            ->whereNotNull('Received_Date')
            ->whereNotNull('Signed_Date');

        // Apply date filters if provided
        if ($start_date && $end_date) {
            // Filter documents processed (signed) between these dates
            $signatoriesQuery->whereBetween('Signed_Date', [$start_date, $end_date]);
        }

        // Retrieve metrics by joining offices and signatories
        $analytics = Office::select(
            'offices.Office_Name',
            DB::raw('AVG(TIMESTAMPDIFF(DAY, signatories.Received_Date, signatories.Signed_Date)) as avg_processing_time_days'),
            DB::raw('AVG(TIMESTAMPDIFF(HOUR, signatories.Received_Date, signatories.Signed_Date)) as avg_processing_time_hours'),
            DB::raw('AVG(TIMESTAMPDIFF(MINUTE, signatories.Received_Date, signatories.Signed_Date)) as avg_processing_time_minutes'),
            DB::raw('COUNT(signatories.id) as documents_processed')
        )
            ->joinSub($signatoriesQuery, 'signatories', function ($join) {
                $join->on('offices.id', '=', 'signatories.Office_ID');
            })
            ->groupBy('offices.Office_Name')
            ->get()
            ->map(function ($office) {
                $office->performance_score = $office->avg_processing_time_days / max($office->documents_processed, 1);
                $office->Office_Name = $this->abbreviateOfficeName($office->Office_Name);
                return $office;
            });

        // Get the top-performing office based on the performance score
        $topPerformingOffice = $analytics->sortBy('performance_score')->first();

        // Monthly processed documents (based on signed date)
        $monthlyQuery = Signatory::query()
            ->whereNotNull('Received_Date')
            ->whereNotNull('Signed_Date');

        // Apply date filters to monthly query if provided
        if ($start_date && $end_date) {
            $monthlyQuery->whereBetween('Signed_Date', [$start_date, $end_date]);
        }

        $monthlyProcessedDocuments = $monthlyQuery
            ->select(DB::raw('MONTH(Signed_Date) as month'), DB::raw('COUNT(*) as documents_processed'))
            ->groupBy(DB::raw('MONTH(Signed_Date)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $monthlyProcessedDocumentsData = array_fill(0, 12, 0);
        foreach ($monthlyProcessedDocuments as $month => $data) {
            $monthlyProcessedDocumentsData[$month - 1] = $data->documents_processed;
        }

        // Compute overall average processing time in days
        $allSignatoriesQuery = Signatory::whereNotNull('Received_Date')->whereNotNull('Signed_Date');

        if ($start_date && $end_date) {
            $allSignatoriesQuery->whereBetween('Signed_Date', [$start_date, $end_date]);
        }

        $averageProcessingTime = $allSignatoriesQuery->get()
            ->map(function ($signatory) {
                $received = Carbon::parse($signatory->Received_Date);
                $signed = Carbon::parse($signatory->Signed_Date);
                return $received->diffInDays($signed);
            })
            ->average();

        $averageProcessingTime = $averageProcessingTime ? round($averageProcessingTime, 2) : 0;

        // Documents approved this month
        $approvedThisMonthQuery = Signatory::whereNotNull('Signed_Date')
            ->whereMonth('Signed_Date', Carbon::now()->month);

        if ($start_date && $end_date) {
            $approvedThisMonthQuery->whereBetween('Signed_Date', [$start_date, $end_date]);
        }

        $approvedThisMonth = $approvedThisMonthQuery->count();

        // Active signatories in the last 6 months
        $activeSignatoriesQuery = Signatory::whereNotNull('Signed_Date')
            ->where('Signed_Date', '>=', Carbon::now()->subMonths(6));

        if ($start_date && $end_date) {
            $activeSignatoriesQuery->whereBetween('Signed_Date', [$start_date, $end_date]);
        }

        $activeSignatories = $activeSignatoriesQuery->distinct('Office_ID')
            ->count('Office_ID');

        return view('analytics.index', compact(
            'analytics',
            'months',
            'monthlyProcessedDocumentsData',
            'topPerformingOffice',
            'averageProcessingTime',
            'approvedThisMonth',
            'activeSignatories'
        ))->with([
            'start_date' => $start_date ? $start_date->format('Y-m-d') : null,
            'end_date' => $end_date ? $end_date->format('Y-m-d') : null,
        ]);
    }


    // Helper function to abbreviate office names
    private function abbreviateOfficeName($officeName)
    {
        return $this->officeAbbreviations[$officeName] ?? $officeName;
    }
}
