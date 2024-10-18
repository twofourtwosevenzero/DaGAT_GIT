<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    @extends('layouts.app')
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/dagat_logo.png') }}">

    @vite(['resources/css/sidebar.css', 'resources/css/dashboard.css'])
    @include('includes.sidebar')
</head>
<body>
@section('content')
<section class="home-section">
    <div class="container">
        <div class="container bg-light rounded content-section">
            <br>
            <h3>&nbsp;Dashboard</h3>
            <br>
            <!-- Card box -->
            <div class="row text-center">
                <div class="col-md-3 col-12 mb-2">
                    <div class="card border-left-primary shadow">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: blue;">
                                Total Documents
                            </div>
                            <div class="mb-0">{{ $totalDocuments }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <div class="card border-left-warning shadow">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: orange;">
                                Pending
                            </div>
                            <div class="mb-0">{{ $pendingDocuments }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <div class="card border-left-success shadow">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: green;">
                                Approved
                            </div>
                            <div class="mb-0">{{ $approvedDocuments }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <div class="card border-left-info shadow">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: purple;">
                                Archived
                            </div>
                            <div class="mb-0">{{ $archivedDocuments }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Progress Section -->
            <div class="card shadow mb-2">
                <div class="card-header py-2">
                    <h2 class="text-header">Current Progress</h2>
                </div>
                <div class="card-body">
                    @foreach($documents as $document)
                    <h4 class="small font-weight-bold">{{ $document['description'] }} <span style="position: relative; top: 1px; float: right;">{{ number_format($document['progress'], 2) }}%</span></h4>
                    <div class="progress mb-2">
                        <div class="progress-bar" role="progressbar" style="width: {{ $document['progress'] }}%" aria-valuenow="{{ $document['progress'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Logs Section -->
            <div class="card shadow mb-2">
                <div class="card-header py-2">
                    <h2 class="text-header">Recent Logs</h2>
                    <a href="{{ route('activitylog.index') }}" class="btn btn-primary">View more</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-light">
                        <thead class="table-header">
                        <tr>
                            <th>File Name</th>
                            <th>Status</th>
                            <th>Office</th>
                            <th>Date and Time</th>
                        </tr>
                        </thead>
                        <tbody class="table-body">
                        @foreach($recentLogs->take(5) as $log) <!-- Limiting the recent logs to 5 -->
                        <tr>
                            <td>{{ $log->document->Description }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->signatory->office->Office_Name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->Timestamp)->format('F j, Y g:i A') }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Office Metrics Section -->
            <div class="card shadow mb-2">
                <div class="card-header py-2">
                    <h2 class="text-header">Office Metrics</h2>
                    <a href="{{ route('analytics.index') }}" class="btn btn-primary">View more</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-light">
                        <thead class="table-header">
                        <tr>
                            <th>Office Name</th>
                            <th>Avg. Processing Time (Days)</th>
                            <th>Avg. Processing Time (Hours)</th>
                            <th>Avg. Processing Time (Minutes)</th>
                            <th>Documents Processed</th>
                        </tr>
                        </thead>
                        <tbody class="table-body">
                        @foreach($officeMetrics as $metric)
                        <tr>
                            <td>{{ $metric['Office_Name'] }}</td>
                            <td>{{ number_format($metric['avg_processing_time_days'], 2) }}</td>
                            <td>{{ number_format($metric['avg_processing_time_hours'], 2) }}</td>
                            <td>{{ number_format($metric['avg_processing_time_minutes'], 2) }}</td>
                            <td>{{ $metric['documents_processed'] }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9gybBogGz1FdKUbw5K6Uis61z1CrcaN5V0m0Pv8JENhIlHXEJ4U"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
</body>
</html>
