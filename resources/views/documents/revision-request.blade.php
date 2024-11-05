@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Request a Revision for Document: {{ $document->Description }}</h2>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Revision Request Form --}}
    <form action="{{ route('documents.submit-revision-request', $document->id) }}" method="POST">
        @csrf
        
        {{-- Office Pin --}}
        <div class="mb-3">
            <label for="office_pin" class="form-label">Enter Office Pin</label>
            <input 
                type="password" 
                name="office_pin" 
                class="form-control" 
                id="office_pin" 
                maxlength="6" 
                required 
                placeholder="Enter your Office Pin"
                value="{{ old('office_pin') }}"
            >
        </div>
        
        {{-- Type of Revision --}}
        <div class="mb-3">
            <label for="revision_type" class="form-label">Type of Revision</label>
            <select 
                class="form-select" 
                name="revision_type" 
                id="revision_type" 
                required
            >
                <option value="" disabled selected>Select a Revision Type</option>
                <option value="Full Revision" {{ old('revision_type') == 'Full Revision' ? 'selected' : '' }}>Full Revision</option>
                <option value="Grammar Revision" {{ old('revision_type') == 'Grammar Revision' ? 'selected' : '' }}>Grammar Revision</option>
                <option value="Content Update" {{ old('revision_type') == 'Content Update' ? 'selected' : '' }}>Content Update</option>
                <option value="Formatting Issue" {{ old('revision_type') == 'Formatting Issue' ? 'selected' : '' }}>Formatting Issue</option>
                <option value="Data Accuracy" {{ old('revision_type') == 'Data Accuracy' ? 'selected' : '' }}>Data Accuracy</option>
                <option value="Compliance Issue" {{ old('revision_type') == 'Compliance Issue' ? 'selected' : '' }}>Compliance Issue</option>
                <option value="Missing Information" {{ old('revision_type') == 'Missing Information' ? 'selected' : '' }}>Missing Information</option>
                <option value="Legal Compliance" {{ old('revision_type') == 'Legal Compliance' ? 'selected' : '' }}>Legal Compliance</option>
                <option value="Other" {{ old('revision_type') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        
        {{-- Reason for Revision --}}
        <div class="mb-3">
            <label for="revision_reason" class="form-label">Reason for Revision</label>
            <textarea 
                name="revision_reason" 
                class="form-control" 
                id="revision_reason" 
                placeholder="Specify the reason for revision" 
                required
            >{{ old('revision_reason') }}</textarea>
        </div>
        
        {{-- Submit Button --}}
        <button type="submit" class="btn btn-primary">Submit Revision Request</button>
    </form>
</div>
@endsection
