<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovedFile;
use App\Models\DocumentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class ArchiveController extends Controller
{
    public function listFiles()
    {
        $files = ApprovedFile::with('documentType')->get();
        $documentTypes = DocumentType::all(); // Fetch all document types for the filter & upload form
        return view('archives.archive', compact('files', 'documentTypes'));
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,docx,xlsx|max:2048',
            'name' => 'required|string|max:100',
            'document_type_id' => 'required|exists:document_types,id',
            'approved_date' => 'nullable|date' // Optional date input
        ]);

        try {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $filename, 'public');

            $fileRecord = new ApprovedFile();
            $fileRecord->name = $request->name;
            $fileRecord->path = $path;
            $fileRecord->document_type_id = $request->document_type_id;
            $fileRecord->approved_date = $request->approved_date ? Carbon::parse($request->approved_date) : Carbon::now();
            $fileRecord->save();

            return redirect()->route('archives.listFiles')->with('success', 'File uploaded successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while uploading the file. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred. Please contact support.');
        }
    }

    public function destroy($id)
    {
        try {
            $file = ApprovedFile::find($id);
            if ($file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
                $file->delete();
                return redirect()->route('archives.listFiles')->with('success', 'File deleted successfully.');
            } else {
                return redirect()->route('archives.listFiles')->with('error', 'File not found.');
            }
        } catch (\Exception $e) {
            return redirect()->route('archives.listFiles')->with('error', 'An error occurred while deleting the file. Please try again.');
        }
    }
}
