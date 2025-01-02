@extends('backend.layouts.main')
@section('title', 'Add Report')
@section('content')
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

                            <!-- No Labels Form -->
                            <form method="post" class="row g-3"
                                action="{{ isset($client) ? URL::to('client-update') : URL::to('/save-client') }} "
                                id="createclient">
                                @csrf


                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Name" id="name"
                                        name="name" value="">
                                </div>

                                <div class="col-md-6">
                                    <select class="form-control" id="question" name="question">
                                        @if (isset($questions))
                                            @foreach ($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->text }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <input type="number" class="form-control" placeholder="Score" id="score"
                                        name="score" value="">
                                </div>

                                <div class="col-md-6">
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>

                                <div class="col-md-6">
                                    <select class="form-control" id="result" name="result">
                                        <option value="Good">Good</option>
                                        <option value="Average">Average</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <select class="form-control" id="pdf_version" name="pdf_version">
                                        <option value="version1">Version 1</option>
                                        <option value="version2">Version 2</option>
                                    </select>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form><!-- End No Labels Form -->

                            <script>
                                $(document).ready(function() {
                                    // Autocomplete for client names
                                    $("#name").autocomplete({
                                        source: function(request, response) {
                                            $.ajax({
                                                url: "{{ URL::to('/get-clients') }}",
                                                dataType: "json",
                                                data: {
                                                    term: request.term
                                                },
                                                success: function(data) {
                                                    response(data);
                                                }
                                            });
                                        },
                                        minLength: 2,
                                    });

                                    // Fetch questions and display them
                                    $.ajax({
                                        url: "{{ URL::to('/get-questions') }}",
                                        dataType: "json",
                                        success: function(data) {
                                            $.each(data, function(index, question) {
                                                $('#questions').append(
                                                    '<div class="col-md-12">' +
                                                    '<label>' + question.text + '</label>' +
                                                    '<input type="number" class="form-control" name="question_' +
                                                    question.id + '" min="1" max="10">' +
                                                    '</div>'
                                                );
                                            });
                                        }
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- jQuery Validate -->
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.3/jquery.validate.min.js"></script>

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
