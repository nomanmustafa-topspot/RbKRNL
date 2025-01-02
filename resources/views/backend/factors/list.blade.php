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
                            <h2 class="card-title">Question Filters</h2>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <select class="form-select form-select" id="filter-type">
                                        <option value="">All Types</option>
                                        @if ($categories)

                                            @foreach ($categories as $category)
                                                <option value="{{ $category['id'] }}">{{ $category['name'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between">
                                <h2 class="card-title">Question List</h2>
                                <button type="button" class="btn btn-primary btn-sm add-new-version" data-bs-toggle="modal"
                                    data-bs-target="#verticalycentered">
                                    Add New
                                </button>
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table admin-factors-list">
                                <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Factor</th>
                                        <th>Type</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody id="factors-list">
                                    @foreach ($factors as $factor)
                                        <tr>
                                            <td>{{ $factor['id'] }}</td>
                                            <td>{{ $factor['text'] }}</td>
                                            <td>{{ $factor->category['name'] }}</td>
                                            <td>{{ $factor['created_at']->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Add Pagination Links -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <!-- Records Count on the Left -->
                                <div class="text-muted">
                                    Showing {{ $factors->firstItem() }} to {{ $factors->lastItem() }} of {{ $factors->total() }} results
                                </div>

                                <!-- Pagination Buttons on the Right -->
                                <div>
                                    {{ $factors->links('pagination::bootstrap-5') }}
                                </div>
                            </div>


                            <!-- End Table with stripped rows -->

                        </div>
                    </div>
                    <!--  modal ajax  -->
                    <div class="modal fade" id="verticalycentered" tabindex="-1" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Questions</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="saveFactorForm" action="{{ route('factor.save') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="factor" class="form-label">Factor</label>
                                            <input type="text" class="form-control" name="text" id="text"
                                                required>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="type" class="form-label">Question Type</label>
                                                <select class="form-select" name="category_id" id="category_id">
                                                    @if ($categories)

                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category['id'] }}">{{ $category['name'] }}
                                                            </option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                            </div>


                                        </div>
                                        <div class="row mb-3">

                                            {{-- <div class="col-md-6">
                                                <label for="result" class="form-label">Result</label>

                                                    <select class="form-select" name="result" id="result" required>
                                                        <option value="Good">Good</option>
                                                        <option value="Poor">Poor</option>
                                                        <option value="Average">Average</option>
                                                    </select>
                                            </div> --}}

                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <script>
        $(document).ready(function() {
            // Save factor form submission
            $('#saveFactorForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('type', $('#type').val()); // Add filters before submission

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,

                success: function(response) {

                if (response.success) {
                    toastr.success(response.success);
                    $('#verticalycentered').modal('hide');
                    $('#saveFactorForm')[0].reset(); // Reset the form
                    var newRow = `<tr>
                    <td>${response.data.id}</td>
                    <td>${response.data.text}</td>
                    <td>${response.data.type}</td>
                    <td>${new Date(response.data.created_at).toLocaleString()}</td>
                    </tr>`;
                    $('.admin-factors-list tbody').append(newRow);
                } else {
                    toastr.error(response.message);
                }
                },
                error: function() {
                toastr.error('File upload failed.');
                },
            });
            });

            // Fetch filtered data with pagination
            function fetchFilteredData(page = 1) {
                $.ajax({
                    url: '{{ route('getFactorList') }}',
                    method: 'GET',
                    data: {
                        type: $('#filter-type').val(),
                        page: page, // Include current page
                    },
                    success: function(response) {
                        // Replace the table content
                        $('#factors-list').empty();
                        response.factors.data.forEach(function(factor) {
                            $('#factors-list').append(`
                                <tr>
                                    <td>${factor.id}</td>
                                    <td>${factor.text}</td>
                                    <td>${factor.category.name}</td>
                                    <td>${new Date(factor.created_at).toLocaleString()}</td>
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
            $('#filter-type').on('change', function() {
                fetchFilteredData();
            });

            // Initial fetch
            fetchFilteredData();
        });
    </script>

@endsection
