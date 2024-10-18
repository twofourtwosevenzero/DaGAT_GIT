<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovedFile;
use App\Models\DocumentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArchiveController extends Controller
{
    
    public function listFiles()
{
    $files = \App\Models\ApprovedFile::with('documentType')->get();
    $documentTypes = DocumentType::all(); // Fetch document types
    
    return view('archives.archive', compact('files', 'documentTypes')); // Pass document types to the view
}

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,docx,xlsx|max:2048',
            'name' => 'required|string|max:100',
            'document_type_id' => 'required|exists:document_types,id',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $filename, 'public');

        $fileRecord = new \App\Models\ApprovedFile();
        $fileRecord->name = $request->name;
        $fileRecord->path = $path;
        $fileRecord->document_type_id = $request->document_type_id;
        $fileRecord->approved_date = Carbon::now();
        $fileRecord->save();

        return redirect()->route('archives.listFiles')->with('success', 'File uploaded successfully.');
    }


    public function destroy($id)
    {
        $file = ApprovedFile::find($id);

        // Delete the file
        if ($file) {
            // Debugging line
            Log::info('File path: ' . $file->path);

            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
                Log::info('File deleted: ' . $file->path); // Log success
            } else {
                Log::warning('File does not exist: ' . $file->path); // Log failure
            }
            $file->delete();
        }

        return redirect()->route('archives.listFiles')->with('success', 'File deleted successfully');
    }
    
    
}
