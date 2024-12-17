<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document Tracker</title>

    @extends('layouts.app')
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('Images/dagat_logo.png') }}">

    <style type="text/css">
        <style type="text/css">
        .floating-btn {
            width: 50px;
            height: 50px;
            background: #011826;
            display: flex;
            justify-content: center;
            color: white;
            position: fixed;
            right: 30px;
            bottom: 30px;
            border-radius: 50%;
            z-index: 1;
            transition: 0.3s ease;
            text-decoration: none
        }

        .floating-btn:hover {
            background: rgb(189, 218, 255);
            cursor: pointer;
            color: #011826;
            border: 1px solid #011826;
        }

        .bx-plus {
            font-size: 30px;
            margin-top: 20%;
        }

        #btn1{
            text-decoration: underline;
            font-weight: 700;
        }
    </style>
    </style>

    @vite(['resources/css/sidebar.css', 'resources/css/documents.css', 'resources/js/sidebar.js', 'resources/js/documents.js'])
    @include('includes.sidebar')
</head>

<body>
    <section class="home-section">
        @section('content')
            <div class="container">
                <div class="home-content">
                </div>
                <div class="container bg-light rounded">
    <br>
    <div class="d-flex justify-content-between align-items-center">
        <h3>&nbsp;Document Tracker</h3>
        <!-- Manage Document Types Button -->
        <a href="{{ route('document-types.index') }}" class="btn btn-warning d-flex align-items-center">
            <i class='bx bx-cog' style="margin-right: 5px;"></i> Manage Document Types
        </a>
    </div>
    <hr>
    <br>
    <h5>&nbsp;List of Documents</h5>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="table-responsive">
        <table id="example" class="table table-bordered table-hover table-light">
            <thead class="table-header">
                <tr>
                    <th>Description</th>
                    <th>Document Type</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>QR Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-body">
                @foreach ($documents as $document)
                <tr>
                    <td scope="row">
                        <a href="{{ route('documents.show', $document->id) }}"
                            class="btn-view-detail" target="_blank">
                            <strong>{{ $document->Description }}</strong>
                        </a>
                    </td>
                    <td>{{ $document->documentType->DT_Type }}</td>
                    <td>{{ $document->status->Status_Name }}</td>
                    <td>{{ $document->Date_Created }}</td>
                    <td>
                        <div>
                            <button class="btn-qr" data-bs-toggle="modal" data-bs-target="#qrModal{{ $document->id }}" id="btn1">Generate</button>
                        </div>
                        <div class="modal fade" id="qrModal{{ $document->id }}" tabindex="-1" aria-labelledby="qrModalLabel{{ $document->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="qrModalLabel{{ $document->id }}">QR Code</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center" id="modal-QR" style="align-items:center; left:10px;">
                                        <img src="{{ asset('storage/' . $document->qrcode->QR_Image) }}" alt="QR Code" class="img-fluid">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="{{ asset('storage/' . $document->qrcode->QR_Image) }}" download class="btn-download btn" style="background-color: #042940; color: #fff;">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm" onclick="openModal(this)" data-id="{{ $document->id }}"
                            data-description="{{ $document->Description }}" data-document-type="{{ $document->DT_ID }}"
                            data-signatories="{{ json_encode($document->signatories->pluck('Office_ID')->toArray() ?? []) }}">
                            <i class='bx bx-edit'></i>
                        </button>
                        <!-- Delete Button that triggers the modal -->
                        <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $document->id }}">
                            <i class='bx bx-trash'></i>
                        </button>
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $document->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $document->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('documents.destroy', $document->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $document->id }}">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this document?</p>
                                            <div class="form-group">
                                                <label for="deleteReason{{ $document->id }}">Reason for deletion:</label>
                                                <textarea class="form-control" id="deleteReason{{ $document->id }}" name="deleteReason" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- Button Add Document Modal -->
<div class="container-add">
    <!-- Button trigger modal -->
    <button class="floating-btn" data-bs-toggle="modal" data-bs-target="#addDocumentModal" style="background: #011826; border: 2px solid #ffffff;">
        <i class='bx bx-plus'></i>
    </button>
</div>


<!-- Add Modal-->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="addDocumentModalLabel">
                    <i class='bx bx-upload'></i> Add Document
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li><i class='bx bx-error'></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <!-- Document Name -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label required">Document Name</label>
                                        <input type="text" name="description" id="description"
                                            class="form-control" required>
                                    </div>
                                    <!-- Document Type -->
                                    <div class="mb-3">
                                        <label for="document_type" class="form-label required">Document Type</label>
                                        <select name="document_type" id="document_type_modal" class="form-select"
                                            required>
                                            <option value="" disabled selected>Select Document Type</option>
                                            @foreach ($documentTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->DT_Type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Document File -->
                                    <div class="mb-3">
                                        <label for="document_file" class="form-label required">Document File</label>
                                        <input type="file" name="document_file" id="document_file"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <!-- Signatories -->
                                    <div class="mb-3">
                                        <label class="form-label required">Signatories</label>
                                        <!-- Signatory Categories -->
                                        <div class="accordion accordion-flush" id="signatoriesAccordionModal">
                                            <!-- College Offices -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingCollegeModal">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCollegeModal" aria-expanded="false" aria-controls="collapseCollegeModal">
                                                        College Offices
                                                    </button>
                                                </h2>
                                                <div id="collapseCollegeModal" class="accordion-collapse collapse" aria-labelledby="headingCollegeModal" data-bs-parent="#signatoriesAccordionModal">
                                                    <div class="accordion-body">
                                                        @foreach ($offices->slice(23, 7) as $office)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="signatories[]"
                                                                    value="{{ $office->id }}" class="form-check-input signatory-checkbox-modal"
                                                                    id="signatoryModal{{ $office->id }}">
                                                                <label class="form-check-label"
                                                                    for="signatoryModal{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Local Council Offices -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingLocalCouncilModal">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLocalCouncilModal" aria-expanded="false" aria-controls="collapseLocalCouncilModal">
                                                        Local Council Offices
                                                    </button>
                                                </h2>
                                                <div id="collapseLocalCouncilModal" class="accordion-collapse collapse" aria-labelledby="headingLocalCouncilModal" data-bs-parent="#signatoriesAccordionModal">
                                                    <div class="accordion-body">
                                                        @foreach ($offices->slice(30, 7) as $office)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="signatories[]"
                                                                    value="{{ $office->id }}" class="form-check-input signatory-checkbox-modal"
                                                                    id="signatoryModal{{ $office->id }}">
                                                                <label class="form-check-label"
                                                                    for="signatoryModal{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Admin Offices -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingAdminModal">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminModal" aria-expanded="false" aria-controls="collapseAdminModal">
                                                        Admin Offices
                                                    </button>
                                                </h2>
                                                <div id="collapseAdminModal" class="accordion-collapse collapse" aria-labelledby="headingAdminModal" data-bs-parent="#signatoriesAccordionModal">
                                                    <div class="accordion-body">
                                                        @foreach ($offices->slice(0, 14) as $office)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="signatories[]"
                                                                    value="{{ $office->id }}" class="form-check-input signatory-checkbox-modal"
                                                                    id="signatoryModal{{ $office->id }}">
                                                                <label class="form-check-label"
                                                                    for="signatoryModal{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Services Offices -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingServicesModal">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServicesModal" aria-expanded="false" aria-controls="collapseServicesModal">
                                                        Services Offices
                                                    </button>
                                                </h2>
                                                <div id="collapseServicesModal" class="accordion-collapse collapse" aria-labelledby="headingServicesModal" data-bs-parent="#signatoriesAccordionModal">
                                                    <div class="accordion-body">
                                                        @foreach ($offices->slice(15, 7) as $office)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="signatories[]"
                                                                    value="{{ $office->id }}" class="form-check-input signatory-checkbox-modal"
                                                                    id="signatoryModal{{ $office->id }}">
                                                                <label class="form-check-label"
                                                                    for="signatoryModal{{ $office->id }}">{{ $office->Office_Name }}</label>
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
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class='bx bx-x'></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-maroon">
                            <i class='bx bx-upload'></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Modal -->
<style>
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
        border: 1px solid #ddd;
    }
    .accordion-button::after {
        filter: invert(29%) sepia(89%) saturate(4417%) hue-rotate(354deg) brightness(85%) contrast(101%);
    }
    .form-check-input:checked {
        background-color: #58151c;
        border-color: #58151c;
    }
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }
    .modal-content {
        border-radius: 8px;
    }
    .modal-header {
        border-bottom: 1px solid #dee2e6;
    }
    .modal-footer {
        border-top: 1px solid #dee2e6;
    }
    .card {
        border: 1px solid #ddd;
    }
    .card-body {
        padding: 1.5rem;
    }
    .form-control, .form-select {
        border-radius: 4px;
    }
    .btn-close-white {
        filter: invert(1);
    }
</style>

<!-- JavaScript to Handle Predefined Signatories in Modal -->
<script>
    // Pass the predefined signatories from Laravel to JavaScript
    var predefinedSignatories = @json($predefinedSignatories);

    document.addEventListener('DOMContentLoaded', function() {
        var documentTypeSelectModal = document.getElementById('document_type_modal');
        var signatoryCheckboxesModal = document.querySelectorAll('.signatory-checkbox-modal');

        function updateSignatoriesModal() {
            var selectedDocumentTypeId = documentTypeSelectModal.value;

            // Get the predefined signatories for the selected document type
            var signatoryIds = predefinedSignatories[selectedDocumentTypeId] || [];

            // Uncheck all signatories
            signatoryCheckboxesModal.forEach(function(checkbox) {
                checkbox.checked = false;
            });

            // Check the predefined signatories
            signatoryIds.forEach(function(signatoryId) {
                var checkbox = document.getElementById('signatoryModal' + signatoryId);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        // Update signatories when the document type changes
        documentTypeSelectModal.addEventListener('change', updateSignatoriesModal);

        // Trigger the update when the modal is shown
        $('#addDocumentModal').on('shown.bs.modal', function () {
            updateSignatoriesModal();
        });
    });
</script>

                <!-- Edit Modal -->
                <div id="editModal" class="modal fade">
                    <div class="modal-dialog custom-modal-width">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title custom-modal-title">Edit Document</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <!-- Edit Modal Body-->
                            <div class="modal-body">
                                <form id="edit-form" method="POST" enctype="multipart/form-data"
                                    class="custom-modal-body">
                                    @csrf
                                    <div class="mb-1 form-group">
                                        @method('PUT') <!-- or PATCH depending on your route configuration -->
                                        <div class="flex-container">
                                            <div class="flex-item-left">
                                                <div class="mb-1 form-group">
                                                    <label for="description" class="form-label required">Document
                                                        Name</label>
                                                    <input type="text" name="description" id="description"
                                                        class="form-control" required>
                                                </div>
                                                <div class="mb-1 form-group">
                                                    <label for="document_type" class="form-label required">Document
                                                        Type</label>
                                                    <select name="document_type" id="document_type" class="form-control"
                                                        required>
                                                        @foreach ($documentTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->DT_Type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-1 py-2 form-group">
                                                    <label for="document_file" class="form-label">Document
                                                        File</label>
                                                    <input type="file" name="document_file" id="document_file"
                                                        class="form-control">
                                                </div>
                                                <div class="mb-1 form-group">
                                                    <label for="signatories"
                                                        class="form-label required">Signatories</label>
                                                    <label class="category-label">College Offices</label>
                                                    <div id="college-signatories">
                                                        @foreach ($offices->slice(23, 7) as $office)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="signatories[]"
                                                                    value="{{ $office->id }}" class="form-check-input"
                                                                    id="signatory{{ $office->id }}">
                                                                <label class="form-check-label"
                                                                    for="signatory{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <label class="category-label">Local Council Offices</label>
                                                    <div id="local-council-signatories">
                                                        @foreach ($offices->slice(30, 7) as $office)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="signatories[]"
                                                                    value="{{ $office->id }}" class="form-check-input"
                                                                    id="signatory{{ $office->id }}">
                                                                <label class="form-check-label"
                                                                    for="signatory{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-item-right">
                                                <div class="mb-1 form-group">
                                                    <label class="category-label">Admin Offices</label>
                                                    <div id="admin-signatories">
                                                        @foreach ($offices->slice(0, 14) as $office)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="signatories[]"
                                                                    value="{{ $office->id }}" class="form-check-input"
                                                                    id="signatory{{ $office->id }}">
                                                                <label class="form-check-label"
                                                                    for="signatory{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <label class="category-label">Services Offices</label>
                                                    <div id="services-signatories">
                                                        @foreach ($offices->slice(15, 7) as $office)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="signatories[]"
                                                                    value="{{ $office->id }}" class="form-check-input"
                                                                    id="signatory{{ $office->id }}">
                                                                <label class="form-check-label"
                                                                    for="signatory{{ $office->id }}">{{ $office->Office_Name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" id="btn-add" class="btn btn-primary">Update</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Edit Modal End -->


<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/documents.js') }}"></script>

    @endsection
</body>

</html>
