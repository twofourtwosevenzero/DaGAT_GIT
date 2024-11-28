<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Office;
use App\Models\QuickResponseCode;
use App\Models\Signatory;
use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\RevisionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentApproved;

class DocumentController extends Controller
{
public function index(Request $request)
{
    $query = Document::with('user', 'documentType', 'status');
    $deletedStatus = Status::where('Status_Name', 'Deleted')->first();

    if ($request->has('show_deleted') && $request->show_deleted == true) {
        $documents = $query->get();
    } else {
        $documents = $query->where('Status_ID', '!=', $deletedStatus->id)->get();
    }

    $documentTypes = DocumentType::all();
    $offices = Office::all();

    // Fetch predefined signatories for all document types
    $predefinedSignatories = DB::table('document_type_signatories')
        ->select('document_type_id', 'signatory_id')
        ->get()
        ->groupBy('document_type_id')
        ->map(function ($item) {
            return $item->pluck('signatory_id')->toArray();
        });

    return view('documents.index', compact('documents', 'documentTypes', 'offices', 'predefinedSignatories'));
    
    }
    public function create()
    {   
        $predefinedSignatories = DB::table('document_type_signatories')
        ->select('document_type_id', 'signatory_id')
        ->get()
        ->groupBy('document_type_id')
        ->map(function ($item) {
            return $item->pluck('signatory_id')->toArray();
        });
        $documentTypes = DocumentType::all();
        $offices = Office::all();
        return view('documents.create', compact('documentTypes', 'offices', 'predefinedSignatories'));
    }

    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'document_type' => 'required|exists:document_types,id',
            'document_file' => 'required|file|mimes:pdf,doc,docx',
            'signatories' => 'nullable|array', // Make signatories optional, as predefined ones can exist
            'signatories.*' => 'exists:offices,id',
        ]);
    
        // Create a new document entry
        $document = Document::create([
            'user_id' => Auth::id(),
            'DT_ID' => $validatedData['document_type'],
            'Description' => $validatedData['description'],
            'Status_ID' => 1, // Assuming 1 is the 'Pending' status
            'Date_Created' => now(),
            'Document_File' => $request->file('document_file')->store('documents', 'public'),
        ]);
    
        // Generate the QR code for the document
        $localIP = env('APP_URL', 'http://192.168.254.107:8000'); // Use your local IP
        $qrCodeUrl = $localIP . '/qrcode/scan/' . $document->id;
        $qrCode = QrCode::format('svg')->size(200)->generate($qrCodeUrl);
        $qrCodePath = 'qrcodes/' . $document->id . '.svg';
        Storage::disk('public')->put($qrCodePath, $qrCode);
    
        $qrcode = QuickResponseCode::create([
            'Docu_ID' => $document->id,
            'QR_Image' => $qrCodePath,
            'Date_Created' => now(),
            'Usage_Count' => 0,
        ]);
    
        // Fetch predefined signatories for the selected document type
        $predefinedSignatories = DB::table('document_type_signatories')
            ->where('document_type_id', $validatedData['document_type'])
            ->pluck('signatory_id');
    
        // Merge predefined signatories with manually selected ones (if any)
        $signatories = $predefinedSignatories->toArray();
    
        if (!empty($validatedData['signatories'])) {
            $signatories = array_merge($signatories, $validatedData['signatories']);
        }
    
        // Remove duplicate signatories if any
        $signatories = array_unique($signatories);
    
        // Attach all signatories (predefined + manually selected) to the document
        foreach ($signatories as $officeId) {
            Signatory::create([
                'QRC_ID' => $qrcode->id,
                'Office_ID' => $officeId,
                'Status_ID' => 1, // Assuming 1 is the 'Pending' status
                'Received_Date' => null,
                'Signed_Date' => null,
            ]);
        }
    
        // Redirect back to the documents index with success message
        return redirect()->route('documents.index')->with('success', 'Document uploaded with predefined signatories and QR code generated.');
    }
    

    public function show($id)
    {
        $document = Document::with([
            'user',
            'documentType',
            'status',
            'qrcode.signatories.office',
            'qrcode.signatories.status'
        ])->findOrFail($id);

        return view('documents.show', compact('document'));
    }

    public function scan($qrcode)
    {
        $qrCode = QuickResponseCode::find($qrcode);
        if (!$qrCode) {
            return redirect()->back()->with('error', 'Invalid QR Code');
        }
    
        $document = $qrCode->document;
    
        // Check if the document has a "Received" action in activity_logs
        $isReceived = ActivityLog::where('Docu_ID', $document->id)
                                  ->where('action', 'Received')
                                  ->exists();
    
        return view('documents.scan', compact('qrCode', 'document', 'isReceived'));
    }
    

    public function verify(Request $request, $id)
    {
        Log::info('Verifying Office PIN:', ['office_pin' => $request->office_pin]);

        $validatedData = $request->validate([
            'office_pin' => 'required|string',
        ]);

        $office = Office::where('Office_Pin', $validatedData['office_pin'])->first();

        if (!$office) {
            Log::error('Invalid Office PIN:', ['office_pin' => $request->office_pin]);
            return redirect()->back()->withErrors(['office_pin' => 'Invalid Office PIN']);
        }

            // Store the Office ID in the session
        session(['current_office_id' => $office->id]);

        $qrcode = QuickResponseCode::findOrFail($id);
        $signatory = Signatory::where('QRC_ID', $qrcode->id)->where('Office_ID', $office->id)->first();

        if (!$signatory) {
            Log::error('Signatory not found:', ['QRC_ID' => $qrcode->id, 'Office_ID' => $office->id]);
            return redirect()->back()->withErrors(['office_pin' => 'Signatory not found for the given QR code and Office.']);
        }

        Log::info('Signatory found:', ['signatory' => $signatory]);

        $verificationCount = ActivityLog::where('Docu_ID', $signatory->QRC_ID)
            ->where('Sign_ID', $signatory->id)
            ->whereIn('action', ['Received', 'Approved'])
            ->count();

        if ($verificationCount >= 2) {
            Log::error('Office PIN entered too many times:', ['office_pin' => $request->office_pin]);
            return redirect()->back()->withErrors(['office_pin' => 'Your office has already approved this document.']);
        }

        $statusMessage = '';

        if ($signatory->Status_ID == 1) {
            $signatory->update([
                'Status_ID' => 2, // Received by Office
                'Received_Date' => now(),
            ]);
            ActivityLog::create([
                'Docu_ID' => $document->id, // Corrected: Use the Document ID
                'Sign_ID' => $signatory->id,
                'action' => 'Received',
                'Timestamp' => now(),
            ]);
            $statusMessage = 'Document has been Received.';
        } elseif ($signatory->Status_ID == 2) {
            $signatory->update([
                'Status_ID' => 3, // Approved by Office
                'Signed_Date' => now(),
            ]);
            ActivityLog::create([
                'Docu_ID' => $document->id, // Corrected: Use the Document ID
                'Sign_ID' => $signatory->id,
                'action' => 'Approved',
                'Timestamp' => now(),
            ]);
            $statusMessage = 'Document has been Approved.';
            
            $this->sendSignatoryApprovalNotification($document, $signatory);
        }
    

        Log::info('Signatory status updated:', ['signatory' => $signatory]);

        $allApproved = Signatory::where('QRC_ID', $qrcode->id)->where('Status_ID', '<', 3)->doesntExist();

        if ($allApproved) {
            $document = $qrcode->document;
            $document->update([
                'Status_ID' => 3, // Assuming 3 is the 'Approved' status
                'Date_Approved' => now(),
            ]);
            ActivityLog::create([
                'Docu_ID' => $document->id,
                'Sign_ID' => null,
                'action' => 'Fully Approved',
                'Timestamp' => now(),
            ]);

            $this->sendApprovalNotification($document);
        }

        $qrcode->increment('Usage_Count');

        Log::info('Signatory status updated successfully.');

        return view('documents.scan', [
            'qrCode' => $qrcode,
            'document' => $qrcode->document,
            'statusMessage' => $statusMessage,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'document_type' => 'required|exists:document_types,id',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx',
            'signatories' => 'required|array',
            'signatories.*' => 'exists:offices,id',
        ]);

        $document = Document::findOrFail($id);
        $document->update([
            'DT_ID' => $validatedData['document_type'],
            'Description' => $validatedData['description'],
            'Document_File' => $request->file('document_file') 
                ? $request->file('document_file')->store('documents', 'public') 
                : $document->Document_File,
        ]);

        Signatory::where('QRC_ID', $document->qrcode->id)->delete();
        foreach ($validatedData['signatories'] as $officeId) {
            Signatory::create([
                'QRC_ID' => $document->qrcode->id,
                'Office_ID' => $officeId,
                'Status_ID' => 1, // Assuming 1 is the 'Pending' status
                'Received_Date' => null,
                'Signed_Date' => null,
            ]);
        }

        if ($document->Status_ID == 3) { // Assuming 3 is the 'Approved' status
            $this->sendApprovalNotification($document);
        }

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        // Validate the delete reason
        $validatedData = $request->validate([
            'deleteReason' => 'required|string|max:255',
        ]);

        // Retrieve the document
        $document = Document::findOrFail($id);

        // Retrieve the authenticated user
        $user = Auth::user();

        // Log the deletion with user association
        ActivityLog::create([
            'Docu_ID' => $document->id,
            'Sign_ID' => null,
            'action' => 'Deleted',
            'Timestamp' => now(),
            'reason' => $validatedData['deleteReason'],
            'user_id' => $user->id, // Associate with the authenticated user
            'requested_by' => null,  // Set to null if not applicable
        ]);

        // Update the document's status to 'Deleted'
        $deletedStatus = Status::where('Status_Name', 'Deleted')->firstOrFail();
        $document->update([
            'Status_ID' => $deletedStatus->id,
        ]);

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }


    protected function sendApprovalNotification($document)
    {
        $lastApprovedSignatory = Signatory::where('QRC_ID', $document->qrcode->id)
            ->where('Status_ID', 3) // Assuming 3 is the 'Approved by Office' status
            ->orderBy('Signed_Date', 'desc')
            ->first();
    
        $lastApprovedOffice = $lastApprovedSignatory ? $lastApprovedSignatory->office : null;
    
        $recipientEmails = [
            'dbjbsantos02094@usep.edu.ph',
        ]; // Replace with the actual recipient emails
    
        Mail::to($recipientEmails)->send(new DocumentApproved($document, $lastApprovedOffice));
    }
    
    // Show the revision request form
    public function showRevisionRequestForm($documentId)
    {
        $document = Document::findOrFail($documentId);
        return view('documents.revision-request', compact('document'));
    }
    
    // Process the revision request form submission
    public function submitRevisionRequest(Request $request, $documentId)
    {
        $document = Document::findOrFail($documentId);
    
        $validatedData = $request->validate([
            'revision_reason' => 'required|string|max:255',
            'revision_type' => 'required|in:Full Revision,Grammar Revision,Content Update,Formatting Issue,Data Accuracy,Compliance Issue,Missing Information,Legal Compliance,Other',
            'office_pin' => 'required|string',
        ]);
    
        $office = Office::where('Office_Pin', $validatedData['office_pin'])->first();
    
        if (!$office) {
            return redirect()->back()->withErrors(['office_pin' => 'Invalid Office PIN'])->withInput();
        }
    
        // Create the revision request
        RevisionRequest::create([
            'document_id' => $document->id,
            'signatory_id' => null,
            'revision_type' => $validatedData['revision_type'],
            'revision_reason' => $validatedData['revision_reason'],
            'requested_by' => $office->id,
        ]);
    
        // Log the revision request in the activity logs
        ActivityLog::create([
            'Docu_ID' => $document->id,
            'Sign_ID' => null,
            'action' => 'Revision Requested',
            'Timestamp' => now(),
            'reason' => $validatedData['revision_reason'],
            'requested_by' => $office->id,
        ]);
    
        // Send email notification
        $this->sendRevisionRequestNotification($document, $office, $validatedData['revision_type'], $validatedData['revision_reason']);
    
        return redirect()->route('documents.show', $documentId)->with('success', 'Revision request submitted successfully.');
    }
    
    protected function sendRevisionRequestNotification($document, $office, $revisionType, $revisionReason)
{
    // Get the recipient emails. Assuming the document has a user associated
    $recipientEmails = ['dbjbsantos02094@usep.edu.ph']; // You can modify this as needed

    Mail::to($recipientEmails)->send(new \App\Mail\DocumentRevisionRequested($document, $office, $revisionType, $revisionReason));
}

protected function sendSignatoryApprovalNotification($document, $signatory)
{

    // Prepare recipient emails
    $recipientEmails = ['dbjbsantos02094@usep.edu.ph'];

    Mail::to($recipientEmails)->send(new \App\Mail\SignatoryApprovedNotification($document, $signatory));
}

    public function getRecentActivity()
{
    $recentLogs = ActivityLog::with('document', 'signatory.office')
        ->orderBy('Timestamp', 'desc')
        ->take(5) // limit to the most recent 5 activities, adjust as needed
        ->get();

    return response()->json($recentLogs);
}

public function getSignatories($id)
{
    $document = Document::with('qrcode.signatories.office')
        ->where('id', $id)
        ->firstOrFail();

    // Return the signatories and their statuses as JSON
    return response()->json($document->qrcode->signatories);
}

    

}
