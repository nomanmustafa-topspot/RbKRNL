@extends('backend.layouts.main')
@section('title', 'Add Report')
@section('content')
    <style>
        .category-color {
            background-color: #12263E !important;
        }

        .hr-row {
            margin: 0px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .help-block {
            margin-top: 5px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

                            <form id="createclient" action="{{ '/save-report' }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label for="client_id" class="form-label">Select Pdf Template</label>
                                            <select class="form-select" name="pdf_template_id" id="pdf_template_id"
                                                required>
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
                                            <select class="form-select select2" name="client_id" id="client_id" required
                                                style="width: 100%; height: calc(1.5em + .75rem + 2px);">
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
                                            <input type="number" class="form-control" name="website_score"
                                                placeholder="1-100" min="1" max="100">
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
                                                                placeholder="(0-10)" min="0" max="10">
                                                        </div>
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

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- jQuery Validate -->
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.3/additional-methods.min.js"></script>

    <script>
        $(document).ready(function() {
            // Include validation rules
            $("#createclient").validate({
                rules: {
                    client_id: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    website: {
                        required: true
                    },
                    website_score: {
                        required: true
                    },
                    image_url: {
                        required: true,
                        extension: "jpg|jpeg|png|svg"
                    }
                },
                messages: {
                    website: {
                        required: "Website is required"
                    },
                    client_id: {
                        required: "Client Name is required"
                    },
                    type: {
                        required: "Type is required"
                    },
                    website_score: {
                        required: "Score is required"
                    },
                    image_url: {
                        required: "Photo is required",
                        extension: "Please upload a file in these formats only (jpg, jpeg, png, svg)."
                    }
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("help-block");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    const formData = new FormData(form);

                    $.ajax({
                        url: '/check-report',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            if (response.error) {
                                swal('Info!', response.message, 'info');
                            } else if (response.exists) {
                                swal({
                                    title: "Are you sure?",
                                    text: response.message,
                                    type: "warning",
                                    confirmButtonText: "Yes",
                                    showCancelButton: true
                                }).then((result) => {
                                    if (result.value) {
                                        submitReport(formData);
                                    } else if (result.dismiss === 'cancel') {
                                        swal('Cancelled', 'You stayed here :)',
                                            'error');
                                    }
                                });
                            }else{
                                submitReport(formData);
                            }
                        },
                        error: function() {
                            alert(
                                'An error occurred while checking the report. Please try again.'
                                );
                        }
                    });
                }
            });

            $("input[name^='questions'][name$='[value]']").each(function() {
                $(this).rules("add", {
                    required: true,
                    number: true,
                    min: 0,
                    max: 10,
                    messages: {
                        required: "Please enter a score between 0 and 10.",
                        number: "Please enter a valid number.",
                        min: "Minimum score is 0.",
                        max: "Maximum score is 10."
                    }
                });
            });

            $("select[name^='questions'][name$='[result]']").each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please select a result."
                    }
                });
            });

            function submitReport(formData) {
                $.ajax({
                    url: '/save-report',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        swal('Success', response.success, 'success');
                        setTimeout(() => {
                            window.location.href = response.redirectUrl || '/reports';
                        }, 2000);
                    },
                    error: function() {
                        swal('Error!',
                            'An error occurred while generating the report. Please try again.',
                            'error'
                        );
                    }
                });
            }

            $('#client_id').select2({
                placeholder: "-- Select Client --",
                allowClear: true
            });
        });
    </script>

@endsection
