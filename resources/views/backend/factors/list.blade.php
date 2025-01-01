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
                        <div class="card-body p-4">
                            <h2 class="card-title">Question Filters</h2>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <select class="form-select form-select" id="filter-type">
                                        <option value="">All Types</option>
                                        <option value="web-presence">Web Presence</option>
                                        <option value="seo">SEO</option>
                                        <option value="site-content">Site Content</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select form-select" id="filter-value">
                                        <option value="">All Pdf</option>
                                        @isset($pdfs)
                                            @foreach ($pdfs as $pdf)
                                                <option value="{{ $pdf['id'] }}">{{ $pdf['version'] }}</option>
                                            @endforeach
                                        @endisset
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
                            <table class="table admin-factors-list" >
                                <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Factor</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Result</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody id="factors-list">
                                    @foreach ($factors as $factor)
                                        @php
                                            if ($factor['type'] == 'web-presence') {
                                                $factor['type'] = 'Web Presence';
                                            } elseif ($factor['type'] == 'site-content') {
                                                $factor['type'] = 'Site Content';
                                            } elseif ($factor['type'] == 'seo') {
                                                $factor['type'] = 'SEO';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $factor['id'] }}</td>
                                            <td>{{ $factor['factor'] }}</td>
                                            <td>{{ $factor['type'] }}</td>
                                            <td>{{ $factor['value'] }}</td>
                                            <td>{{ $factor['result'] }}</td>
                                            <td>{{ $factor['created_at']->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                            <input type="text" class="form-control" name="factor" id="factor"
                                                required>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="type" class="form-label">Question Type</label>
                                                <select class="form-select" name="type" id="type">
                                                    <option value="web-presence">Web Presence</option>
                                                    <option value="seo">SEO</option>
                                                    <option value="site-content">Site Content</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="value" class="form-label">Value</label>
                                                <input type="number" class="form-control" name="value" id="value"
                                                    placeholder="1 - 10" min="1" max="10" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="result" class="form-label">Pdf Version</label>
                                                <select class="form-select" name="pdf_template_id" id="pdf_template_id">
                                                    @isset($pdfs)
                                                        @foreach ($pdfs as $pdf)
                                                            <option value="{{ $pdf['id'] }}">{{ $pdf['version'] }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="result" class="form-label">Result</label>

                                                    <select class="form-select" name="result" id="result" required>
                                                        <option value="Good">Good</option>
                                                        <option value="Poor">Poor</option>
                                                        <option value="Average">Average</option>
                                                    </select>
                                            </div>

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

            $('#saveFactorForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    beforeSend: function() {
                        // Add filters to the request
                        formData.append('type', $('#type').val());
                        formData.append('pdf_template_id', $('#pdf_template_id').val());
                    },
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response);
                            $('#verticalycentered').modal('hide');
                            var newRow = `<tr>
                            <td>${response.data.id}</td>
                            <td>${response.data.factor}</td>
                            <td>${response.data.type}</td>
                            <td>${response.data.value}</td>
                            <td>${response.data.result}</td>
                            <td>${response.data.created_at}</td>
                          </tr>`;
                            $('.admin-factors-list tbody').append(newRow);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(response) {
                        toastr.error('File upload failed.');
                    }
                });
            });

            function fetchFilteredData() {
                $.ajax({
                    url: '{{ route('getFactorList') }}', // Adjust the route as needed
                    method: 'GET',
                    data: {
                        type: $('#filter-type').val(),
                        value: $('#filter-value').val()
                    },
                    success: function(response) { // Update the factors list
                        $('#factors-list').empty();
                        response.factors.forEach(function(factor) {
                            $('#factors-list').append('<tr>' + '<td>' + factor.id + '</td>' +
                                '<td>' + factor.factor + '</td>' + '<td>' + factor.type +
                                '</td>' + '<td>' + factor.value + '</td>' + '<td>' + factor
                                .result + '</td>' + '<td>' + new Date(factor.created_at)
                                .toLocaleString() + '</td>' + '</tr>');
                        });
                    }
                });
            }

            $('#filter-type, #filter-value').on('change', function() {
                fetchFilteredData();
            });

            // Initial fetch
            fetchFilteredData();
        });
    </script>

@endsection
