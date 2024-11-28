@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('Images/dagat_logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Upload Document</title>
    @vite(['resources/css/archive.css'], 'resources/js/sidebar.js')
</head>
<body>
    <div class="upload-container">
        <i class="fas fa-times close-icon" onclick="window.location.href='{{ url('/archives') }}'"></i>
        <h1>UPLOAD DOCUMENT HERE</h1>
        <form action="/upload" method="post" enctype="multipart/form-data">
            @csrf
            <div class="upload-area" id="upload-area">
                <i class="fas fa-file-upload" id="icon"></i>
                <p>Click or Drag & Drop to Upload</p>
                <div class="file-list" id="file-list"></div>
            </div>
            <input type="file" name="file" id="file" style="display: none;" required>
            
            <label for="name">File Name:</label>
            <input type="text" name="name" id="name" placeholder="File Name" required>
            
            <label for="document_type_id">Document Type:</label>
            <select name="document_type_id" id="document_type_id" required>
                <option value="">Select Document Type</option>
                @foreach($documentTypes as $documentType)
                    <option value="{{ $documentType->id }}">{{ $documentType->DT_Type }}</option>
                @endforeach
            </select>
            
            <button type="submit">Upload</button>
        </form>
    </div>
    <script>
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
    </script>
</body>
</html>