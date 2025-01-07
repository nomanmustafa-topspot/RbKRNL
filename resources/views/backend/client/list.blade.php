@extends('backend.layouts.main')
@section('title', 'PDF List')
@section('content')
    <style>
        .add-new-version {
            width: 90px;
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
                                <h2 class="card-title">Clients List</h2>

                                <button type="button" class="btn btn-primary btn-sm add-new-version" data-bs-toggle="modal"
                                    data-bs-target="#verticalycentered">
                                    Add New
                                </button>
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table admin-clients-list">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Designation</th>
                                        <th>Website</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr id="client-{{ $client['id'] }}">
                                            <td>{{ $client['name'] }}</td>
                                            <td>{{ $client['email'] }}</td>
                                            <td>{{ $client['designation'] }}</td>
                                            <td>{{ $client['website'] }}</td>
                                            <td>{{ $client['created_at']->diffForHumans() }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm delete-client" data-id="{{ $client['id'] }}">
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
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Client</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" class="row g-3" id="add_new_client"
                                        id="createclient">
                                        @csrf

                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Email" id="email" name="email" >
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Designation" id="designation" name="designation">
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Website" name="website">
                                        </div>

                                        <div class="col-md-6">
                                            <input type="date" class="form-control" placeholder="date" name="date">
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- <div class="modal fade" id="verticalycentered" tabindex="-1" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Client</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" class="row g-3"
                                        action="{{ isset($client) ? URL::to('client-update') : URL::to('/save-client') }} "
                                        id="createclient">
                                        @csrf
                                        @if (isset($client))
                                            <input type="hidden" name="client_id" value="{{ $client->id }} " />
                                        @endif

                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Name" id="name"
                                                name="name" value="{{ isset($client) ? $client->name : '' }}">
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Email" id="email"
                                                name="email" value="{{ isset($client) ? $client->email : '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Designation"
                                                id="designation" name="designation"
                                                value="{{ isset($client) ? $client->designation : '' }}">
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Website" name="website"
                                                value="{{ isset($client) ? $client->website : '' }}">
                                        </div>

                                        <div class="col-md-6">
                                            <input type="date" class="form-control" placeholder="date" name="date"
                                                value="{{ isset($client) ? $client->date : '' }}">
                                        </div>

                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-primary">{{ isset($client) ? 'Update' : 'Create' }}</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.admin-clients-list').DataTable();

            $('.add-new-version').on('click', function() {
                $('#add_new_client')[0].reset();
            });

            $('#add_new_client').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: '/save-client',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.success) {
                            // Append new data to the table
                            let table = $('.admin-clients-list tbody');
                            let newRow = `
                                    <tr id="client-${data.newItem.id}">
                                        <td>${data.newItem.name}</td>
                                        <td>${data.newItem.email}</td>
                                        <td>${data.newItem.designation}</td>
                                        <td>${data.newItem.website}</td>
                                        <td>${new Date(data.newItem.created_at).toLocaleString()}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm delete-client" data-id="${data.newItem.id}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            table.append(newRow);
                            // Close the modal
                            $('#verticalycentered').modal('hide');
                        } else {
                            // Handle errors
                            alert('Error: ' + data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
            $(document).on('click', '.delete-client', function() {

                let clientId = $(this).data('id');
                swal({
                    text: "Are you sure you want to delete this cleint?",
                    type: "warning",
                    confirmButtonText: "Yes",
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/client/delete/',
                            type: 'POST',
                            data: {
                                id: clientId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                if (data.success) {
                                    // Remove the row from the table
                                    $('#client-' + clientId).remove();
                                    swal('Success', 'Client deleted successfully!', 'success');
                                } else {
                                    swal('Error!',
                                        data.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle AJAX error
                                swal('Error!', 'An error occurred while deleting the Client. Please try again.', 'error');
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
