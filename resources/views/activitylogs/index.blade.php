<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Logs</title>
  @vite(['resources/css/sidebar.css', 'resources/css/activity.css', 'resources/js/sidebar.js'])
</head>

<body>
@include('includes.sidebar')

<section class="home-section">
  <div class="home-content"></div>
  <div class="container bg-light rounded">
    <br>
    <div class="d-flex justify-content-between align-items-center">
      <h3>&nbsp;Activity Logs</h3>
      <div class="filter-container">
        <label for="dateFilter" class="form-label">Filter by Date Range:</label>
        <input type="text" id="dateFilter" class="form-control" placeholder="Select date range" title="Enter date range in MM/DD/YYYY - MM/DD/YYYY format">
        <button id="downloadPdf" class="btn btn-primary">Download PDF</button>
      </div>
    </div>
    <hr>
    <br>
    <div id="activityLogsContainer">
      @php
        $logs = $activityLogs->sortByDesc('Timestamp');
      @endphp
      @foreach($logs as $log)
      <div class="activity-log" data-timestamp="{{ optional($log->Timestamp)->timestamp }}">
          <div>
              <div class="title">{{ $log->document->Description ?? 'N/A' }}</div>
              <div class="description">
                  @if($log->action == 'Deleted')
                      Deleted by {{ $log->user->name ?? 'Unknown User' }}.
                      <br><strong>Reason:</strong> {{ $log->reason ?? 'No reason provided' }}
                      @elseif($log->action == 'Revision Requested')
                      Revision Requested by
                      {{ $log->requestedOffice->Office_Name ?? 'N/A' }}.                  
                  @elseif($log->action == 'Fully Approved')
                      Fully Approved by all signatories.
                  @elseif(!is_null($log->signatory) && !is_null($log->signatory->office))
                      {{ $log->action }} by {{ $log->signatory->office->Office_Name ?? 'N/A' }}.
                  @else
                      {{ $log->action }}
                  @endif
              </div>
          </div>
          <div class="timestamp">
              {{ $log->Timestamp ? \Carbon\Carbon::parse($log->Timestamp)->diffForHumans() : 'N/A' }}
          </div>
      </div>
     @endforeach  
    </div>
    
    <br>
  </div>
  <br> <br>
</section>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('script/sidebar.js') }}"></script>

<script>
  $(document).ready(function() {
      $('#dateFilter').daterangepicker({
          opens: 'left',
          autoUpdateInput: false,
          locale: {
              cancelLabel: 'Clear'
          }
      });

      $('#dateFilter').on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
          filterLogs(picker.startDate, picker.endDate);
      });

      $('#dateFilter').on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
          $('.activity-log').show();
      });

      function filterLogs(startDate, endDate) {
          $('.activity-log').each(function() {
              var logTimestamp = moment.unix($(this).data('timestamp'));
              if (logTimestamp.isBetween(startDate, endDate, null, '[]')) {
                  $(this).show();
              } else {
                  $(this).hide();
              }
          });
      }

      var $activityLogs = $('#activityLogsContainer .activity-log');
      $activityLogs.sort(function(a, b) {
          return $(b).data('timestamp') - $(a).data('timestamp');
      }).appendTo('#activityLogsContainer');

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
