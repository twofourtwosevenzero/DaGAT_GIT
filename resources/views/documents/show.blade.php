@extends('layouts.app')
@vite(['resources/css/sidebar.css', 'resources/css/documents.css', 'resources/js/sidebar.js', 'resources/js/documents.js'])
@section('content')
<div class="container-fluid">
    @include('includes.sidebar')
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">
    
    <div class="card mt-4">
        <div class="card-header">
            <h2 style="margin-top:10px;">Document Details</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <div class="mb-3">
                        <strong>Description:</strong> <span class="text-muted">{{ $document->Description }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Type:</strong> <span class="badge bg-info">{{ $document->documentType->DT_Type }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong> <span class="badge bg-success">{{ $document->status->Status_Name }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Created At:</strong> <span class="text-muted">{{ $document->created_at }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Approved At:</strong> <span class="text-muted">{{ $document->Date_Approved ?? 'Not yet approved' }}</span>
                    </div>
                    <a href="{{ asset('storage/' . $document->Document_File) }}" class="btn btn-primary" target="_blank">View Document</a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 d-flex flex-column align-items-center">
                    @if ($document->qrcode)
                        <div id="qrCodeContainer" class="mb-3">
                            <img src="{{ asset('storage/' . $document->qrcode->QR_Image) }}" alt="QR Code" class="img-fluid" style="max-width: 250px;">
                        </div>
                        <button class="btn btn-secondary mt-2" onclick="printQRCode()">Print QR Code</button>
                    @else
                        <p>No QR code available.</p>
                    @endif
                </div>
            </div>
            
            <h3 class="mt-4">Signatories</h3>
            @if($document->qrcode && $document->qrcode->signatories->isNotEmpty())
                <div class="list-group">
                    @foreach($document->qrcode->signatories as $signatory)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $signatory->office->Office_Name }}
                            <span class="badge bg-{{ $signatory->Status_ID == 1 ? 'secondary' : ($signatory->Status_ID == 2 ? 'warning' : 'success') }}">
                                @if($signatory->Status_ID == 1)
                                    Pending
                                @elseif($signatory->Status_ID == 2)
                                    Received on {{ $signatory->Received_Date }}
                                @elseif($signatory->Status_ID == 3)
                                    Approved on {{ $signatory->Signed_Date }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No signatories available.</p>
            @endif
        </div>
    </div>
</div>

<script>
    function printQRCode() {
        var printContents = document.getElementById('qrCodeContainer').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
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
        <script src="{{ asset('js/sidebar.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                function fetchSignatories() {
                    fetch("{{ route('documents.get-signatories', $document->id) }}")
                        .then(response => response.json())
                        .then(data => {
                            let signatoriesContainer = document.querySelector(".list-group");
                            signatoriesContainer.innerHTML = ""; // Clear current signatories
            
                            data.forEach(signatory => {
                                // Create a list item for each signatory
                                let listItem = document.createElement("div");
                                listItem.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
            
                                let officeName = document.createElement("span");
                                officeName.textContent = signatory.office.Office_Name;
            
                                let statusBadge = document.createElement("span");
                                statusBadge.classList.add("badge");
            
                                // Assign badge class based on Status_ID and set text content accordingly
                                if (signatory.Status_ID == 1) {
                                    statusBadge.classList.add("bg-secondary");
                                    statusBadge.textContent = "Pending";
                                } else if (signatory.Status_ID == 2) {
                                    statusBadge.classList.add("bg-warning");
                                    statusBadge.textContent = `Received on ${signatory.Received_Date}`;
                                } else if (signatory.Status_ID == 3) {
                                    statusBadge.classList.add("bg-success");
                                    statusBadge.textContent = `Approved on ${signatory.Signed_Date}`;
                                }
            
                                listItem.appendChild(officeName);
                                listItem.appendChild(statusBadge);
                                signatoriesContainer.appendChild(listItem);
                            });
                        })
                        .catch(error => console.error('Error fetching signatories:', error));
                }
            
                // Poll for updates every 10 seconds
                setInterval(fetchSignatories, 10000);
            });
            </script>
            
@endsection
