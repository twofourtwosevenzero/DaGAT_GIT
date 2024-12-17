<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the document types.
     */
    public function index()
    {
        $documentTypes = DocumentType::all();
        return view('document-types.index', compact('documentTypes'));
    }

    /**
     * Show the form for creating a new document type.
     */
    public function create()
    {
        return view('document-types.create');
    }

    /**
     * Store a newly created document type in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'DT_Type' => 'required|string|max:255|unique:document-types',
        ]);

        DocumentType::create($validatedData);

        return redirect()->route('document-types.index')->with('success', 'Document Type created successfully.');
    }

    /**
     * Display the specified document type.
     */
    public function show(DocumentType $documentType)
    {
        return view('document-types.show', compact('documentType'));
    }

    /**
     * Show the form for editing the specified document type.
     */
    public function edit(DocumentType $documentType)
    {
        return view('document-types.edit', compact('documentType'));
    }

    /**
     * Update the specified document type in storage.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $validatedData = $request->validate([
            'DT_Type' => 'required|string|max:255|unique:document-types,DT_Type,' . $documentType->id,
        ]);

        $documentType->update($validatedData);

        return redirect()->route('document-types.index')->with('success', 'Document Type updated successfully.');
    }

    /**
     * Remove the specified document type from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return redirect()->route('document-types.index')->with('success', 'Document Type deleted successfully.');
    }

    /**
     * Retrieve signatories associated with a specific document type.
     */
    public function getSignatories($documentTypeId)
    {
        $signatories = DB::table('document_type_signatories')
            ->where('document_type_id', $documentTypeId)
            ->join('offices', 'offices.id', '=', 'document_type_signatories.signatory_id')
            ->select('offices.id', 'offices.Office_Name')
            ->get();

        return response()->json($signatories);
    }
}
