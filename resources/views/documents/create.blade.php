@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-maroon text-white">
            <h3 class="mb-0">Add Document</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>There were some problems with your input:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Document Form -->
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Document Name -->
                        <div class="mb-4">
                            <label for="description" class="form-label required">Document Name</label>
                            <input type="text" name="description" id="description"
                                class="form-control form-control-lg" required>
                        </div>
                        <!-- Document Type -->
                        <div class="mb-4">
                            <label for="document_type" class="form-label required">Document Type</label>
                            <select name="document_type" id="document_type" class="form-select form-select-lg"
                                required>
                                <option value="" disabled selected>Select Document Type</option>
                                @foreach ($documentTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->DT_Type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Document File -->
                        <div class="mb-4">
                            <label for="document_file" class="form-label required">Document File</label>
                            <input type="file" name="document_file" id="document_file"
                                class="form-control form-control-lg" required>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Signatories -->
                        <div class="mb-4">
                            <label class="form-label required">Signatories</label>
                            <!-- Signatory Categories -->
                            <div class="accordion" id="signatoriesAccordion">
                                <!-- College Offices -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCollege">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCollege" aria-expanded="false" aria-controls="collapseCollege">
                                            College Offices
                                        </button>
                                    </h2>
                                    <div id="collapseCollege" class="accordion-collapse collapse" aria-labelledby="headingCollege" data-bs-parent="#signatoriesAccordion">
                                        <div class="accordion-body">
                                            @foreach ($offices->slice(23, 7) as $office)
                                                <div class="form-check">
                                                    <input type="checkbox" name="signatories[]"
                                                        value="{{ $office->id }}" class="form-check-input signatory-checkbox"
                                                        id="signatory{{ $office->id }}">
                                                    <label class="form-check-label"
                                                        for="signatory{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Local Council Offices -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingLocalCouncil">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLocalCouncil" aria-expanded="false" aria-controls="collapseLocalCouncil">
                                            Local Council Offices
                                        </button>
                                    </h2>
                                    <div id="collapseLocalCouncil" class="accordion-collapse collapse" aria-labelledby="headingLocalCouncil" data-bs-parent="#signatoriesAccordion">
                                        <div class="accordion-body">
                                            @foreach ($offices->slice(30, 7) as $office)
                                                <div class="form-check">
                                                    <input type="checkbox" name="signatories[]"
                                                        value="{{ $office->id }}" class="form-check-input signatory-checkbox"
                                                        id="signatory{{ $office->id }}">
                                                    <label class="form-check-label"
                                                        for="signatory{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Admin Offices -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingAdmin">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                                            Admin Offices
                                        </button>
                                    </h2>
                                    <div id="collapseAdmin" class="accordion-collapse collapse" aria-labelledby="headingAdmin" data-bs-parent="#signatoriesAccordion">
                                        <div class="accordion-body">
                                            @foreach ($offices->slice(0, 14) as $office)
                                                <div class="form-check">
                                                    <input type="checkbox" name="signatories[]"
                                                        value="{{ $office->id }}" class="form-check-input signatory-checkbox"
                                                        id="signatory{{ $office->id }}">
                                                    <label class="form-check-label"
                                                        for="signatory{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Services Offices -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingServices">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServices" aria-expanded="false" aria-controls="collapseServices">
                                            Services Offices
                                        </button>
                                    </h2>
                                    <div id="collapseServices" class="accordion-collapse collapse" aria-labelledby="headingServices" data-bs-parent="#signatoriesAccordion">
                                        <div class="accordion-body">
                                            @foreach ($offices->slice(15, 7) as $office)
                                                <div class="form-check">
                                                    <input type="checkbox" name="signatories[]"
                                                        value="{{ $office->id }}" class="form-check-input signatory-checkbox"
                                                        id="signatory{{ $office->id }}">
                                                    <label class="form-check-label"
                                                        for="signatory{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Accordion -->
                        </div>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-maroon">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript to Handle Predefined Signatories -->
<script>
    // Pass the predefined signatories from Laravel to JavaScript
    var predefinedSignatories = @json($predefinedSignatories);

    document.addEventListener('DOMContentLoaded', function() {
        var documentTypeSelect = document.getElementById('document_type');
        var signatoryCheckboxes = document.querySelectorAll('.signatory-checkbox');

        function updateSignatories() {
            var selectedDocumentTypeId = documentTypeSelect.value;

            // Get the predefined signatories for the selected document type
            var signatoryIds = predefinedSignatories[selectedDocumentTypeId] || [];

            // Uncheck all signatories
            signatoryCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });

            // Check the predefined signatories
            signatoryIds.forEach(function(signatoryId) {
                var checkbox = document.getElementById('signatory' + signatoryId);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        // Update signatories when the document type changes
        documentTypeSelect.addEventListener('change', updateSignatories);

        // Trigger the update on page load
        updateSignatories();
    });
</script>
@endsection

@push('styles')
<style>
    /* USeP Maroon Color */
    .bg-maroon {
        background-color: #58151c;
    }
    .btn-maroon {
        background-color: #58151c;
        color: #fff;
    }
    .btn-maroon:hover {
        background-color: #660000;
        color: #fff;
    }
    .form-label.required::after {
        content: " *";
        color: red;
    }
    .accordion-button {
        background-color: #f8f9fa;
        color: #58151c;
    }
    .accordion-button:not(.collapsed) {
        color: #fff;
        background-color: #58151c;
    }
    .accordion-button:focus {
        box-shadow: none;
    }
    .accordion-item {
        border: none;
    }
    .accordion-button::after {
        filter: invert(29%) sepia(89%) saturate(4417%) hue-rotate(354deg) brightness(85%) contrast(101%);
    }
    .form-check-input:checked {
        background-color: #800000;
        border-color: #800000;
    }
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }
    .card {
        border: none;
    }
    .card-header {
        border-bottom: none;
    }
    .card-body {
        padding: 2rem;
    }
</style>
@endpush
