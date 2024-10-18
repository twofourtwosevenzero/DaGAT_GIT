<?php
namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::all();
        return view('document_types.index', compact('documentTypes'));
    }

    public function create()
    {
        return view('document_types.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'DT_Type' => 'required|string',
        ]);

        DocumentType::create($validatedData);

        return redirect()->route('document-types.index')->with('success', 'Document Type created successfully.');
    }

    public function show(DocumentType $documentType)
    {
        return view('document_types.show', compact('documentType'));
    }

    public function edit(DocumentType $documentType)
    {
        return view('document_types.edit', compact('documentType'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $validatedData = $request->validate([
            'DT_Type' => 'required|string',
        ]);

        $documentType->update($validatedData);

        return redirect()->route('document-types.index')->with('success', 'Document Type updated successfully.');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return redirect()->route('document-types.index')->with('success', 'Document Type deleted successfully.');
    }

    //NEW HERE
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
