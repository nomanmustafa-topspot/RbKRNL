@extends('backend.layouts.main')
@section('title', 'PDF List')
@section('content')
    <style>
        .add-new-version {
            width: 135px;
            height: 35px;
        }
    </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

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
                            <div class="d-flex justify-content-between">
                                <h2 class="card-title">Pdf List</h2>

                                <button type="button" class="btn btn-primary btn-sm add-new-version" data-bs-toggle="modal"
                                    data-bs-target="#verticalycentered">
                                    Add New Version
                                </button>
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table datatable-admin-pdf-list admin-pdf-list">
                                <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Name</th>
                                        <th>Version</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pdfs as $pdf)
                                        <tr id="pdf-{{ $pdf['id'] }}">

                                            <td>{{ $pdf['id'] }}</td>
                                            <td>{{ $pdf['name'] }}</td>
                                            <td>{{ $pdf['version'] }}</td>
                                            <td>{{ $pdf['created_at']->diffForHumans() }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm delete-pdf" data-id="{{ $pdf['id'] }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="verticalycentered" tabindex="-1" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Create New Version PDF</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="uploadPdfForm" action="{{ route('upload.pdf') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="pdf" class="form-label">Upload PDF</label>
                                            <input type="file" accept=".pdf" class="form-control" name="pdf"
                                                id="pdf" required>
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

   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.datatable-admin-pdf-list').DataTable({
                dom: 'Bfrtip', 
                buttons: [
                    'excel',
                ]
            });

            $('#uploadPdfForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            swal('Success', data.message, 'success');
                            // Append new data to the table
                            let table = $('.admin-pdf-list tbody');
                            let newRow = `
                                    <tr id="pdf-${data.newItem.id}">
                                        <td>${data.newItem.id}</td>
                                        <td>${data.newItem.name}</td>
                                        <td>${data.newItem.version}</td>
                                        <td>${new Date(data.newItem.created_at).toLocaleString()}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm delete-pdf" data-id="${data.newItem.id}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            table.append(newRow);
                            // Close the modal
                            $('#verticalycentered').modal('hide');
                        } else {
                            swal('Error!',
                                data.message,
                                'error'
                            );
                        }

                    },
                    error: function(response) {

                        swal('Error!','File Not Upload',
                            'error'
                        );
                    }
                });
            });

            $(document).on('click', '.delete-pdf', function() {

                let pdfId = $(this).data('id');
                swal({
                    text: "Are you sure you want to delete this PDF Version?",
                    type: "warning",
                    confirmButtonText: "Yes",
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/pdf/delete/',
                            type: 'POST',
                            data: {
                                id: pdfId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                if (data.success) {
                                    // Remove the row from the table
                                    $('#pdf-' + pdfId).remove();
                                    swal('Success', data.message, 'success');
                                } else {
                                    swal('Error!',
                                        data.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle AJAX error
                                swal('Error!', 'An error occurred while deleting the PDF. Please try again.', 'error');
                                console.error('Error:', error);
                            }
                        });
                    } else if (result.dismiss === 'cancel') {
                        swal('Cancelled', 'You stayed here :)',
                            'error');
                    }
                });
            });
        });
    </script>


@endsection
