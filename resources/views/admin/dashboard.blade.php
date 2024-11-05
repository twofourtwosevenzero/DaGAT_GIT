<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Boxicons CSS -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">
  <!-- Responsive Meta Tag -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{ asset('css/custom-dashboard.css') }}">
  <!-- Vite -->
  @vite(['resources/css/sidebar.css', 'resources/css/dashboard.css'])
</head>

<body>
@include('includes.sidebar')

<section class="home-section">
  <div class="home-content"></div>
  <div class="content-wrapper">
    <div class="container-fluid py-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Welcome back, {{ Auth::user()->name }}!</h3>
        <button class="btn btn-orange" onclick="location.href='{{ route('documents.create') }}';">
          <i class='bx bx-upload'></i> Upload New Document
        </button>
      </div>
    <!-- Start of Grid Layout -->
    <div class="row g-4">
      <!-- First Grid Item: Document Status Cards -->
      <div class="col-lg-6">
        <div class="card mb-4 h-100">
          <div class="card-header">
            <h4 class="mb-0">Your Documents</h4>
          </div>
          <div class="card-body">
            <div class="row g-3">
              @foreach($documentStatuses as $statusName => $status)
                <div class="col-md-6 col-sm-12">
                  <div class="status-card" onclick="location.href='{{ route('documents.index', ['status' => $statusName]) }}';">
                    <div class="icon-container">
                      <i class='bx bx-file'></i>
                    </div>
                    <div class="status-details">
                      <span class="status-name">{{ ucfirst($statusName) }}</span>
                      <span class="status-count">{{ $status->documents_count }}</span>
                    </div>
                  </div>
                </div>
              @endforeach
              <div class="col-md-6 col-sm-12">
                <div class="status-card" onclick="location.href='{{ route('archives.listFiles') }}';">
                  <div class="icon-container">
                    <i class='bx bx-archive'></i>
                  </div>
                  <div class="status-details">
                    <span class="status-name">Archived Files</span>
                    <span class="status-count">{{ $approvedFiles }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

<!-- Second Grid Item: Recent Activity -->
<div class="col-lg-6">
  <div class="card mb-4 h-100">
      <div class="card-header">
          <h4 class="mb-0">Recent Activity</h4>
      </div>
      <div class="card-body recent-logs-container">
          @forelse($recentLogs as $log)
              <div class="recent-log-item">
                  <div class="icon-container">
                      <i class='bx bx-file'></i>
                  </div>
                  <div class="log-content">
                      @if($log->action == 'Deleted')
                          <span class="log-description">
                              <strong>{{ $log->document->Description ?? 'N/A' }}</strong> was deleted by 
                              {{ $log->user->name ?? 'Unknown User' }} because {{ $log->reason ?? 'no reason provided' }}.
                          </span>
                      @elseif($log->action == 'Revision Requested')
                          <span class="log-description">
                              <strong>{{ $log->document->Description ?? 'N/A' }}</strong> Revision Requested by 
                              {{ $log->requestedOffice->Office_Name ?? 'N/A' }}.
                          </span>
                      @elseif($log->action == 'Fully Approved')
                          <span class="log-description">
                              <strong>{{ $log->document->Description ?? 'N/A' }}</strong> Fully Approved by all signatories.
                          </span>
                      @elseif($log->signatory && $log->signatory->office)
                          <span class="log-description">
                              <strong>{{ $log->document->Description ?? 'N/A' }}</strong> {{ $log->action }} by 
                              {{ $log->signatory->office->Office_Name ?? 'N/A' }}.
                          </span>
                      @else
                          <span class="log-description">
                              <strong>{{ $log->document->Description ?? 'N/A' }}</strong> {{ $log->action }}.
                          </span>
                      @endif
                      <br>
                      <span class="log-timestamp">{{ optional($log->Timestamp)->diffForHumans() ?? 'N/A' }}</span>
                  </div>
              </div>
          @empty
              <p class="text-center">No recent activity.</p>
          @endforelse
      </div>
  </div>
</div>


      <!-- Third Grid Item: Documents Processed Over Time Chart -->
      <div class="col-lg-6">
        <div class="card mb-4 h-100">
          <div class="card-header">
            <h4 class="mb-0">Documents Processed Over Time</h4>
          </div>
          <div class="card-body p-2 chart-container">
            <canvas id="documentsProcessedOverTimeChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Fourth Grid Item: Quick Analytics Cards -->
      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-header">
            <h4 class="mb-0">Quick Analytics</h4>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-12">
                <div class="analytics-card">
                  <div class="icon-container">
                    <i class='bx bx-time-five'></i>
                  </div>
                  <div class="analytics-details">
                    <span class="analytics-name">Average Processing Time</span>
                    <span class="analytics-value">{{ $averageProcessingTime }} days</span>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="analytics-card">
                  <div class="icon-container">
                    <i class='bx bx-check-circle'></i>
                  </div>
                  <div class="analytics-details">
                    <span class="analytics-name">Approved This Month</span>
                    <span class="analytics-value">{{ $approvedThisMonth }}</span>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="analytics-card">
                  <div class="icon-container">
                    <i class='bx bx-user'></i>
                  </div>
                  <div class="analytics-details">
                    <span class="analytics-name">Active Signatories</span>
                    <span class="analytics-value">{{ $activeSignatories }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- End of Quick Analytics Cards -->
      </div>

    </div> <!-- End of Grid Layout -->
  </div>
</section>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/sidebar.js') }}"></script>

<script>
  // Documents Processed Over Time Chart
  const ctx = document.getElementById('documentsProcessedOverTimeChart').getContext('2d');
  const documentsProcessedOverTimeChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: {!! json_encode($months) !!},
      datasets: [{
        label: 'Documents Processed',
        data: {!! json_encode(array_values($monthlyProcessedDocumentsData)) !!},
        backgroundColor: 'rgba(245, 166, 35, 0.2)', // Use the orange color with opacity
        borderColor: '#F5A623',
        borderWidth: 2,
        pointBackgroundColor: '#F5A623',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: '#F5A623',
        fill: true,
        tension: 0.3,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          ticks: {
            color: '#6c757d'
          },
          grid: {
            display: false
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: '#6c757d'
          },
          grid: {
            color: '#e9ecef'
          }
        }
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: '#fff',
          titleColor: '#343a40',
          bodyColor: '#343a40',
          borderColor: '#e9ecef',
          borderWidth: 1,
        }
      }
    }
  });
</script>

</body>
</html>
