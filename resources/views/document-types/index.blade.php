<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manage Document Types</title>

    @extends('layouts.app')
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/dagat_logo.png') }}">

    @vite(['resources/css/sidebar.css', 'resources/css/documents.css', 'resources/js/sidebar.js', 'resources/js/documents.js'])
    @include('includes.sidebar')

    <style>
        .bg-light-custom {
            background-color: #f8f9fa;
        }

        .table-header th {
            background-color: #58151c;
            color: white;
            text-align: center;
        }

        .table-body td {
            text-align: center;
        }

        .btn-maroon {
            background-color: #58151c;
            color: #fff;
        }

        .btn-maroon:hover {
            background-color: #660000;
            color: #fff;
        }

        .bg-maroon {
            background-color: #58151c !important;
        }

        .btn-close-white {
            filter: invert(1);
        }

        /* Reduce default margins/padding that may cause large spacing */
        body, html {
            margin: 0;
            padding: 0;
        }

        section.home-section {
            padding-top: 0;
            margin-top: 0;
        }
    </style>
</head>
<body>
@section('content')
<section class="home-section">
    <div class="container bg-light-custom rounded mt-2">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h3 class="mb-0">Manage Document Types</h3>
            <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                <i class='bx bx-plus' style="margin-right: 5px;"></i> Add Document Type
            </button>
        </div>
        <hr class="mt-0 mb-3">
        <h5>List of Document Types</h5>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table id="example" class="table table-bordered table-hover table-light">
                <thead class="table-header">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-body">
                @foreach ($documentTypes as $type)
                    <tr>
                        <td>{{ $type->id }}</td>
                        <td>{{ $type->DT_Type }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editDocumentTypeModal"
                                    data-id="{{ $type->id }}"
                                    data-type="{{ $type->DT_Type }}">
                                <i class='bx bx-edit'></i> Edit
                            </button>
                            <form action="{{ route('document-types.destroy', $type->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Document Type?')">
                                    <i class='bx bx-trash'></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addDocumentTypeModal" tabindex="-1" aria-labelledby="addDocumentTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('document-types.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-maroon text-white">
                        <h5 class="modal-title" id="addDocumentTypeModalLabel">
                            <i class='bx bx-plus-circle'></i> Add Document Type
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="DT_Type" class="form-label">Document Type</label>
                        <input type="text" name="DT_Type" id="DT_Type" class="form-control" placeholder="Enter document type" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            <i class='bx bx-x'></i> Close
                        </button>
                        <button type="submit" class="btn btn-maroon">
                            <i class='bx bx-save'></i> Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editDocumentTypeModal" tabindex="-1" aria-labelledby="editDocumentTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editDocumentTypeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-maroon text-white">
                        <h5 class="modal-title" id="editDocumentTypeModalLabel">
                            <i class='bx bx-edit'></i> Edit Document Type
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="edit_DT_Type" class="form-label">Document Type</label>
                        <input type="text" name="DT_Type" id="edit_DT_Type" class="form-control" placeholder="Enter new document type" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            <i class='bx bx-x'></i> Close
                        </button>
                        <button type="submit" class="btn btn-maroon">
                            <i class='bx bx-save'></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editDocumentTypeModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var type = button.getAttribute('data-type');

            var form = document.getElementById('editDocumentTypeForm');
            form.action = `/documenttypes/${id}`;
            document.getElementById('edit_DT_Type').value = type;
        });
    });
</script>
</body>
</html>
