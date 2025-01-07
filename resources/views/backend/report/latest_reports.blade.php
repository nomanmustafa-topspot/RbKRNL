@extends('backend.layouts.main')
@section('title', 'PDF List')
@section('content')
    <style>
        .add-new-version {
            width: 90px;
            height: 35px;
        }
    </style>

    <main id="main" class="main">
        @if (session('success'))
            <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif


        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body px-4">
                            <h2 class="card-title">Client Filters</h2>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label for="">Clients</label>
                                    <select class="form-select form-select" id="filter-type">
                                        <option value="">All</option>
                                        @if ($clients)
                                            @foreach ($clients as $client)
                                                <option value="{{ $client['name'] }}">{{ $client['name'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label for="">Created PDF</label>
                                    <input type="date" id="filter-date" class="form-control">
                                </div>
                                <div class="col-md-2 mt-4">
                                    <button class="btn btn-primary" id="filter-btn">Reset</button>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between">
                                <h2 class="card-title">Reports</h2>
                            </div>
                            <table class="table admin-factors-list">
                                <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Clinet</th>
                                        <th>Website</th>
                                        <th>Score</th>
                                        <th>PDF</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody id="factors-list">
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>{{ $report['id'] }}</td>
                                            <td>{{ $report->client['name'] }}</td>
                                            <td>{{ $report->client['website'] }}</td>
                                            <td>{{ $report['score'] }}</td>
                                            <td>
                                                <a href="{{  url('download-pdf/${reports.file_path}') }}" class="btn btn-primary">
                                                    Download PDF
                                                </a>
                                            </td>

                                            <td>{{ $report['created_at'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Add Pagination Links -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <!-- Records Count on the Left -->
                                <div class="text-muted">
                                    Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} results
                                </div>

                                <!-- Pagination Buttons on the Right -->
                                <div>
                                    {{ $reports->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        $(document).ready(function() {
            // Fetch filtered data with pagination
            function fetchFilteredData(page = 1) {
                $.ajax({
                    url: '{{ route('getReportsList') }}',
                    method: 'GET',
                    data: {
                        name: $('#filter-type').val(),
                        date: $('#filter-date').val(),
                        page: page, // Include current page
                    },
                    success: function(response) {
                        // Replace the table content
                        $('#factors-list').empty();
                        response.reports.data.forEach(function(reports) {
                            $('#factors-list').append(`
                                <tr>
                                    <td>${reports.id}</td>
                                    <td>${reports.client.name}</td>
                                    <td>${reports.client.website}</td>
                                    <td>${reports.score}</td>
                                    <td>
                                        <a href="{{ url('download-pdf/${reports.file_path}') }}" class="btn btn-primary">
                                            Download PDF
                                        </a>
                                    </td>
                                    <td>${new Date(reports.created_at).toLocaleString()}</td>
                                </tr>
                            `);
                        });

                        // Update pagination links
                        $('#pagination-links').html(response.links);
                    },
                    error: function() {
                        toastr.error('Failed to fetch data.');
                    },
                });
            }

            // Handle pagination links
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchFilteredData(page);
            });

            // Apply filters
            $('#filter-type , #filter-date').on('change', function() {

                fetchFilteredData();
            });

            // Initial fetch
            fetchFilteredData();

            $('#filter-btn').on('click', function() {
                // Reset the filter inputs
                $('#filter-type').val('');
                $('#filter-date').val('');
                fetchFilteredData();
            });
        });
    </script>

@endsection
