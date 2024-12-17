<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('Images/dagat_logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Metrics</title>
    @vite(['resources/css/sidebar.css', 'resources/css/analytics.css', 'resources/js/sidebar.js', 'resources/js/documents.js'])
</head>
<body>
@include('includes.sidebar')

<section class="home-section">
    <div class="home-content"></div>
    <div class="container bg-light rounded" id="metricsContent" style="padding: 30px">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Office Metrics</h3>
            <button id="downloadPdf" class="btn btn-primary" style="background-color: #470303; border-color: #470303;">Download PDF</button>
        </div>
        <hr>

                <!-- Date Filter Form -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                   value="{{ request('start_date') ?? $start_date }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                   value="{{ request('end_date') ?? $end_date }}">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-maroon mt-2" style="background-color: #470303; color: #fff;">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
                <!-- End of Date Filter Form -->

        <div class="container px-4 text-center">
            <div class="row gx-5">
                <div class="col" style="margin-top: 20px">
                    <div class="card p-3 text-center" style="border-radius: 15px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <div class="icon" style="font-weight:700;" data-bs-toggle="tooltip" data-bs-placement="top" title="The office with the least average processing time for document approvals.">
                            Top Performing Office
                        </div>
                        <script>
                            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl);
                            });
                        </script>
                        <div>
                            <h3 class="inner-h3" style="font-size:25px;">
                                {{ $topPerformingOffice?->Office_Name ?? 'No data available' }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 20px">
                    <div class="card p-3 text-center" style="border-radius: 15px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <div class="icon" style="font-weight:700;">AVG Processing Days</div>
                        <div>
                            <h3 class="inner-h3" style="font-size:25px;">
                                {{ round($topPerformingOffice?->avg_processing_time_days ?? 0, 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 20px">
                    <div class="card p-3 text-center" style="border-radius: 15px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <div class="icon" style="font-weight:700;">Documents Processed</div>
                        <div>
                            <h3 class="inner-h3" style="font-size:25px;">
                                {{ $topPerformingOffice?->documents_processed ?? 'No data available' }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="container px-4 overflow-hidden">
            <div class="row gx-5">
                <div class="col" style="background-color: #DDDDDD; border-radius: 15px; padding-bottom: 5px">
                    <div class="p-3 text-center" style="color: #333; font-family: 'Quicksand', sans-serif; font-size: larger; font-weight: bold;">OFFICE METRICS CHART</div>
                    <div class="d-flex justify-content-between align-items-center" style="height: 300px;">
                        <!-- Bar Chart -->
                        <div style="position: relative; height: 100%; width: 100%;">
                            <canvas id="officeMetricsChart"></canvas>
                        </div>
                    
                    
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        // Define your 10 main and 10 lighter colors directly in JavaScript
                        const mainColors = [
                            '#9d4004', '#dbb40f', '#db8e0f', '#760000', '#470303',
                            '#131313', '#544e48', '#899581', '#070028', '#6b6b6b'
                        ];

                        const lightColors = [
                            '#b85c21', '#ecca37', '#eaab43', '#bc2323', '#831313',
                            '#444040', '#8d8176', '#c7cfc2', '#3b3361', '#a19c9c'
                        ];

                        // Extracting office names and data from PHP
                        const officeNames = {!! json_encode($analytics->pluck('Office_Name') ?? []) !!};
                        const processingDaysData = {!! json_encode($analytics->pluck('avg_processing_time_days') ?? []) !!};
                        const documentsProcessedData = {!! json_encode($analytics->pluck('documents_processed') ?? []) !!};

                        // Arrays to store dynamically assigned colors for each office
                        const processingDaysColors = officeNames.map((_, index) => {
                            return mainColors[index % 10]; // Cycle through main colors
                        });

                        const documentsProcessedColors = officeNames.map((_, index) => {
                            return lightColors[index % 10]; // Cycle through lighter colors
                        });

                        // Bar Chart
                        var ctxBar = document.getElementById('officeMetricsChart').getContext('2d');
                        var officeMetricsChart = new Chart(ctxBar, {
                            type: 'bar',
                            data: {
                                labels: officeNames,
                                datasets: [{
                                    label: 'Processing Days',
                                    data: processingDaysData,
                                    backgroundColor: processingDaysColors,
                                    borderColor: processingDaysColors,
                                    borderWidth: 1
                                }, {
                                    label: 'Documents Processed',
                                    data: documentsProcessedData,
                                    backgroundColor: documentsProcessedColors,
                                    borderColor: documentsProcessedColors,
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#333'
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: '#333'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#333'
                                        }
                                    }
                                }
                            }
                        });

                        // Pie Chart
                        var ctxPie = document.getElementById('documentsDistributionChart').getContext('2d');
                        var documentsDistributionChart = new Chart(ctxPie, {
                            type: 'pie',
                            data: {
                                labels: officeNames,
                                datasets: [{
                                    data: documentsProcessedData,
                                    backgroundColor: processingDaysColors // Using main colors for the pie chart
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#333'
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

        <br>

        <div class="container px-4 overflow-hidden">
            <div class="row gx-5">
                <div class="col" style="background-color: #DDDDDD; border-radius: 15px; padding-bottom: 15px;">
                    <div class="p-3 text-center" style="color: #333; font-family: 'Quicksand', sans-serif; font-size: larger; font-weight: bold;">DOCUMENTS PROCESSED OVER TIME</div>
                    <div style="position: relative; height: auto; width: 100%;">
                        <canvas id="documentsProcessedOverTimeChart"></canvas>
                        <script>
                            var ctx = document.getElementById('documentsProcessedOverTimeChart').getContext('2d');
                            var documentsProcessedOverTimeChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: {!! json_encode($months ?? []) !!},
                                    datasets: [{
                                        label: 'Documents Processed',
                                        data: {!! json_encode($monthlyProcessedDocumentsData ?? []) !!},
                                        backgroundColor: '#dc8c0c',
                                        borderColor: '#470303',
                                        borderWidth: 1,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        x: {
                                            ticks: {
                                                color: '#333'
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                color: '#333'
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: '#333'
                                            }
                                        }
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="container px-4 overflow-hidden">
            <div class="row gx-5">
                <div class="col" style="background-color: #DDDDDD; border-radius: 15px; padding-bottom: 20px;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="background-color: #470303;">Office Name</th>
                                    <th style="background-color: #470303;">Average Processing Time (Days)</th>
                                    <th style="background-color: #470303;">Average Processing Time (Hours)</th>
                                    <th style="background-color: #470303;">Average Processing Time (Minutes)</th>
                                    <th style="background-color: #470303;">Total Documents Processed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics ?? [] as $data)
                                <tr>
                                    <td>{{ $data->Office_Name ?? 'No data available' }}</td>
                                    <td>{{ round($data->avg_processing_time_days ?? 0, 2) }}</td>
                                    <td>{{ round($data->avg_processing_time_hours ?? 0, 2) }}</td>
                                    <td>{{ round($data->avg_processing_time_minutes ?? 0, 2) }}</td>
                                    <td>{{ $data->documents_processed ?? 'No data available' }}</td>
                                </tr>
                                @endforeach
                                @if(empty($analytics) || $analytics->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">No data available</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
    <br>
</section>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGz1FdKUbw5K6Uis61z1CrcaN5V0m0Pv8JENhIlHXEJ4U" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-QA0CXOQmFK4POnUpaW6pF1QmxVN/cI6E9Drh4u5qIc8flrPUjcbINJZLMhQ8NKWU" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const { jsPDF } = window.jspdf;

        document.getElementById('downloadPdf').addEventListener('click', function () {
            var content = document.getElementById('metricsContent');
            var button = document.getElementById('downloadPdf');

            // Hide the download button before capturing
            button.style.display = 'none';

            // Ensure all fonts are loaded before capturing
            document.fonts.ready.then(function () {
                html2canvas(content, {
                    useCORS: true, // Use this option if you have any cross-origin images
                    scale: 2, // Increase scale for better quality
                    logging: true, // Enable logging for debugging
                    backgroundColor: '#ffffff' // Set background color to white
                }).then(function (canvas) {
                    var doc = new jsPDF('p', 'mm', 'a4');
                    var imgData = canvas.toDataURL('image/png');
                    var imgWidth = 210; // A4 width in mm
                    var pageHeight = 295; // A4 height in mm
                    var imgHeight = canvas.height * imgWidth / canvas.width;
                    var heightLeft = imgHeight;
                    var position = 0;

                    doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        doc.addPage();
                        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    // Show the download button again after capturing
                    button.style.display = 'block';

                    doc.save('office-metrics.pdf');
                }).catch(function (error) {
                    // Show the download button again in case of an error
                    button.style.display = 'block';
                    console.error('Error generating PDF:', error);
                });
            });
        });
    });
</script>
</body>
</html>
