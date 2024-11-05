<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">

    <!-- Vite CSS -->
    @vite(['resources/css/sidebar.css', 'resources/css/archive.css'])

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
    @include('includes.sidebar')

    <section class="home-section">
        <div class="home-content"></div>
        <div class="container bg-light rounded">
            <br>
            <h3>&nbsp;ARCHIVE</h3>
            <hr>
            <div class="container-add">
                <button class="floating-btn" id="uploadButton">
                    <i class='bx bx-plus'></i>
                </button>
            </div>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="table-controls">
                <div class="filter-container">
                    <div>
                        <label for="filterMonth" class="form-label">Filter by Month:</label>
                        <select id="filterMonth" class="form-select form-select-sm">
                            <option value="">All Months</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ date("F", mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="filterYear" class="form-label">Filter by Year:</label>
                        <select id="filterYear" class="form-select form-select-sm">
                            <option value="">All Years</option>
                            @for ($i = date("Y"); $i >= date("Y") - 10; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div id="table-controls-placeholder"></div>
            </div>

            <table id="archiveTable" class="table table-light table-hover table-borderless">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Document Type</th>
                        <th>Approved Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $file)
                    <tr>
                        <td>{{ $file->name }}</td>
                        <td>{{ $file->documentType->DT_Type }}</td>
                        <td>{{ $file->approved_date->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ Storage::url($file->path) }}" target="_blank" class="btn btn-gradientv btn-sm">View File</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        </div>
        <br><br><br>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" action="/upload" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 text-center border rounded py-4" id="upload-area" style="cursor: pointer;">
                            <i class="bx bxs-file-doc bx-lg" id="icon"></i>
                            <p class="mt-2">Click or Drag & Drop to Upload</p>
                            <input type="file" name="file" id="file" class="d-none" required>
                        </div>
                        <div id="file-list" class="mb-3"></div>

                        <div class="mb-3">
                            <label for="name" class="form-label">File Name:</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="File Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="document_type_id" class="form-label">Document Type:</label>
                            <select name="document_type_id" id="document_type_id" class="form-select" required>
                                <option value="">Select Document Type</option>
                                @foreach($documentTypes as $documentType)
                                    <option value="{{ $documentType->id }}">{{ $documentType->DT_Type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/sidebar.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#archiveTable').DataTable({
                "dom": '<"top"l>rt<"bottom"ip><"clear">',
                "language": {
                    "lengthMenu": "Show _MENU_ entries",
                    "search": "Search:"
                },
                "order": [[2, "desc"]] // Sort by the third column (Approved Date) in descending order
            });

            // Move DataTables controls
            $('#table-controls-placeholder').append($('.dataTables_length, .dataTables_filter'));

            $('#filterMonth, #filterYear').on('change', function() {
                table.draw();
            });

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var filterMonth = parseInt($('#filterMonth').val(), 10);
                    var filterYear = parseInt($('#filterYear').val(), 10);
                    var date = new Date(data[2].split('-').reverse().join('-'));
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();

                    if (
                        (isNaN(filterMonth) || filterMonth === month) &&
                        (isNaN(filterYear) || filterYear === year)
                    ) {
                        return true;
                    }
                    return false;
                }
            );

            // Upload modal functionality
            $('#uploadButton').click(function() {
                var uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
                uploadModal.show();
            });

            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('file');
            const fileList = document.getElementById('file-list');
            const fileNameInput = document.getElementById('name');

            uploadArea.addEventListener('click', () => fileInput.click());

            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                handleFiles(e.dataTransfer.files);
            });

            fileInput.addEventListener('change', () => handleFiles(fileInput.files));

            function handleFiles(files) {
                fileList.innerHTML = '';
                for (const file of files) {
                    const listItem = document.createElement('div');
                    listItem.className = 'file-list-item';
                    listItem.textContent = file.name;
                    fileList.appendChild(listItem);
                }
                if (files.length > 0) {
                    fileNameInput.value = files[0].name;
                }
            }
        });
    </script>
</body>

</html>