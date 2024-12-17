<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
  <link rel="icon" type="image/x-icon" href="{{ asset('Images/dagat_logo.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Logs</title>
  @vite(['resources/css/sidebar.css', 'resources/css/activity.css', 'resources/js/sidebar.js'])
</head>

<body>
@include('includes.sidebar')

<section class="home-section">
  <div class="home-content"></div>
  <div class="container bg-light rounded py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Activity Logs</h3>
      <button id="downloadPdf" class="btn btn-primary">Download PDF</button>
    </div>
    <hr>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('activitylogs.index') }}" class="row g-3 mb-4">
        <div class="col-md-2">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                   value="{{ request('start_date', $start_date ?? '') }}">
        </div>
        <div class="col-md-2">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                   value="{{ request('end_date', $end_date ?? '') }}">
        </div>
        <div class="col-md-2">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description" class="form-control"
                   value="{{ request('description', $description ?? '') }}" placeholder="Search by description">
        </div>
        <div class="col-md-2">
            <label for="action" class="form-label">Action</label>
            <input type="text" name="action" id="action" class="form-control"
                   value="{{ request('action', $action ?? '') }}" placeholder="Search by action">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
    <!-- End of Filter Form -->

    @php
        $logs = $activityLogs->sortByDesc('Timestamp');
    @endphp

    <div id="activityLogsContainer">
        @forelse($logs as $log)
            <div class="activity-log">
                <div>
                    <div class="title">{{ $log->document->Description ?? 'N/A' }}</div>
                    <div class="description">
                        @if($log->action == 'Deleted')
                            Deleted by {{ $log->user->name ?? 'Unknown User' }}.
                            <br><strong>Reason:</strong> {{ $log->reason ?? 'No reason provided' }}
                        @elseif($log->action == 'Revision Requested')
                            Revision Requested by {{ $log->office->Office_Name ?? 'Unknown Office' }}.
                            <br><strong>Reason:</strong> {{ $log->reason ?? 'No reason provided' }}
                        @elseif($log->action == 'Fully Approved')
                            Fully Approved by all signatories.
                        @elseif(!is_null($log->signatory))
                            {{ $log->action }} by {{ $log->signatory->office->Office_Name ?? 'Unknown Signatory' }}.
                        @else
                            {{ $log->action }}
                        @endif
                    </div>
                </div>
                <div class="timestamp">
                    {{ $log->Timestamp ? \Carbon\Carbon::parse($log->Timestamp)->diffForHumans() : 'N/A' }}
                </div>
            </div>
        @empty
            <div class="alert alert-info">No activity logs found for the given filters.</div>
        @endforelse
    </div>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('script/sidebar.js') }}"></script>

<script>
  $(document).ready(function() {
    $('#downloadPdf').on('click', function() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF('landscape');

      doc.setFontSize(16);
      doc.text('Activity Logs', 140, 10, null, null, 'center');

      const rows = [];
      $('.activity-log:visible').each(function() {
        const row = [];
        row.push($(this).find('.title').text());
        row.push($(this).find('.description').text());
        row.push($(this).find('.timestamp').text());
        rows.push(row);
      });

      doc.autoTable({
        head: [['Document Description', 'Action', 'Timestamp']],
        body: rows,
        startY: 20,
        theme: 'striped',
        headStyles: { fillColor: [0, 123, 255] },
        columnStyles: {
          0: { cellWidth: 70 },
          1: { cellWidth: 140 },
          2: { cellWidth: 50 }
        },
        styles: { fontSize: 10 }
      });

      doc.save('activity_logs.pdf');
    });
  });
</script>

</body>
</html>
