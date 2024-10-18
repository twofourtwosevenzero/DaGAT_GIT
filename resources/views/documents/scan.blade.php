@extends('layouts.app')
@vite(['resources/css/qrpage.css', 'resources/js/sidebar.js'])
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="{{ asset('images/dagat_logo.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('css/qrpage.css') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document Verification</title>
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100" id="background">
  <div id="white" class="container text-center bg-white p-3 rounded shadow">
    <div class="row mb-4 justify-content-center">
      <div class="col-12 col-md-6">
        <img src="{{ asset('images/school.png') }}" alt="Council Logos" class="img-fluid mx-2">
      </div>
    </div>
    <div class="row mb-3">
      <div class="col">
        <h1>DaGat</h1>
        <p class="dts">Document and Governance Administration Tracker</p>
        <div class="file-name font-weight-bold">
          {{ $document->Description }}
        </div>
        <br>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(isset($statusMessage))
          <div class="alert alert-{{ $statusMessage == 'Document has been Received.' ? 'info' : 'success' }}">
            {{ $statusMessage }}
          </div>
        @else
          <p>Please enter your Office Pin!</p>
          <div id="error-message" class="text-danger mb-3"></div>
          <div class="row justify-content-center">
            <div class="col-auto">
              <div class="input-wrapper">
                <form method="POST" action="{{ route('documents.verify', $qrCode->id) }}">
                  @csrf
                  <input type="password" class="form-control text-center" id="pin-input" name="office_pin" maxlength="6" placeholder="OFFICE PIN">
                  <i class='bx bx-hide eye-icon' id="eye-toggle"></i>
                  <br>
                  <button class="btn btn-primary w-50" id="done" type="submit">Verify</button>
                </form>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>

    <div class="error">
      <a href="#" data-bs-toggle="modal" data-bs-target="#errorModal">
        <i class='bx bxs-error-alt' alt="report" title="Report a Concern"></i>
      </a>
    </div>
  </div>

  <!-- Bootstrap Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="errorModalLabel">Request a Revision</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('documents.request-revision', $document->id) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="revision_type" class="form-label">Type of Revision</label>
              <select class="form-select" name="revision_type" id="revision_type" required>
                <option value="Full Revision">Full Revision</option>
                <option value="Grammar Revision">Grammar Revision</option>
                <option value="Content Update">Content Update</option>
                <option value="Formatting Issue">Formatting Issue</option>
                <option value="Data Accuracy">Data Accuracy</option>
                <option value="Compliance Issue">Compliance Issue</option>
                <option value="Missing Information">Missing Information</option>
                <option value="Legal Compliance">Legal Compliance</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="revision_reason" class="form-label">Reason for Revision</label>
              <textarea name="revision_reason" class="form-control" id="revision_reason" placeholder="Specify the reason for revision" required></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" style="background-color: #800000; border-color:#800000;">Request Revision</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('script/sidebar.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const pinInput = document.getElementById('pin-input');
      const eyeToggle = document.getElementById('eye-toggle');
      eyeToggle.addEventListener('click', () => {
        const isPassword = pinInput.type === 'password';
        pinInput.type = isPassword ? 'text' : 'password';
        pinInput.style.webkitTextSecurity = isPassword ? 'none' : 'disc';
        eyeToggle.classList.toggle('bx-hide');
        eyeToggle.classList.toggle('bx-show');
      });
    });
  </script>
</body>
</html>
