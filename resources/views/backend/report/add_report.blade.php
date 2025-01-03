@extends('backend.layouts.main')
@section('title', 'Add Report')
@section('content')
<style>
    .category-color{
        background-color: #12263E !important;
    }
    .hr-row{
        margin: 0px !important;
    }
    .select2-container .select2-selection--single {
        height: 35px !important;
    }
</style>
    <main id="main" class="main">
        <div class="pagetitle">
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        @if (session('success'))
                            <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                                role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                                role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title"> Make Report</h5>

                            <form action="{{ '/save-report' }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label for="client_id" class="form-label">Select Client</label>
                                            <select class="form-select" name="pdf_template_id" id="pdf_template_id" required >
                                                <option value="">-- Select Pdf Version --</option>
                                                @foreach ($pdfs as $pdf)
                                                    <option value="{{ $pdf->id }}">{{ $pdf->version }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label for="client_id" class="form-label">Select Client</label>
                                            <select class="form-select select2" name="client_id" id="client_id" required style="width: 100%; height: calc(1.5em + .75rem + 2px);">
                                                <option value="">-- Select Client --</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label for="client_id" class="form-label">Website Score</label>
                                            <!-- Image Upload -->
                                            <input type="number" class="form-control" name="website_score"  placeholder="1-100" min="1" max="100">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label for="client_id" class="form-label">Upload Website Image</label>
                                            <!-- Image Upload -->
                                            <input type="file" class="form-control" name="image_url" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <!-- Select Client -->

                                <!-- Categories and Questions -->
                                @foreach ($categories as $category)
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white category-color">
                                            {{ $category->name }}
                                        </div>
                                        <div class="card-body">
                                            @foreach ($category->questions as $question)
                                                <div class="mb-3 mt-2">

                                                    <div class="row">
                                                        <!-- Score -->
                                                        <div class="col-md-7">
                                                            <p>{{ $question->text }}</p>
                                                            <hr class="hr-row">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" class="form-control"
                                                                name="questions[{{ $question->id }}][value]"
                                                                placeholder="(1-10)" min="1" max="10"
                                                                required>
                                                        </div>
                                                        <!-- Result -->
                                                        <div class="col-md-3">
                                                            <select class="form-select"
                                                                name="questions[{{ $question->id }}][result]" required>
                                                                <option value="">-- Select Result --</option>
                                                                <option value="good">Good</option>
                                                                <option value="average">Average</option>
                                                                <option value="poor">Poor</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Submit Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success btn-lg">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- jQuery Validate -->
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.3/jquery.validate.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#client_id').select2({
                placeholder: "-- Select Client --",
                allowClear: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Add your form validation logic here

            $("#createclient").validate({
                rules: {
                    // Specify rules for your form fields
                    name: {
                        required: true,
                    },
                    type: {
                        required: true,
                    },
                    website: {
                        required: true,
                    },
                    score: {
                        required: true,
                    },
                },
                messages: {
                    // Specify custom error messages
                    website: {
                        required: "website is required",
                    },
                    name: {
                        required: "Name is required",
                    },
                    type: {
                        required: "type is required",
                    },
                    score: {
                        required: "score is required",
                    },
                },
                // Specify the submit handler function
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>

@endsection
