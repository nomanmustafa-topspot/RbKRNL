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
                                <h2 class="card-title">Categories List</h2>
                                <button type="button" class="btn btn-primary btn-sm add-new-version" data-bs-toggle="modal"
                                    data-bs-target="#verticalycentered">
                                    Add New
                                </button>
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table admin-category-list">
                                <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Name</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="category-list">
                                    @foreach ($categories as $category)
                                        <tr id="category-{{ $category['id'] }}">
                                            <td>{{ $category['id'] }}</td>
                                            <td>{{ $category['name'] }}</td>
                                            <td>{{ $category['created_at']->diffForHumans() }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm delete-category" data-id="{{ $category['id'] }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                    <!-- Modal Form -->
                    <div class="modal fade" id="verticalycentered" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="modalForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="categoryName" class="form-label">Category Name</label>
                                            <input type="text" class="form-control" id="categoryName" name="name"
                                                required>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.add-new-version').on('click', function() {
                // Clear form fields if needed
                $('#modalForm')[0].reset();
            });

            $('#modalForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: '/category/save',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.success) {
                            // Append new data to the table
                            let table = $('.admin-category-list tbody');
                            let newRow = `
                                    <tr id="category-${data.newItem.id}">
                                        <td>${data.newItem.id}</td>
                                        <td>${data.newItem.name}</td>
                                        <td>${moment(data.newItem.created_at).fromNow()}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm delete-category" data-id="${data.newItem.id}">
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

            $(document).on('click', '.delete-category', function() {
                let categoryId = $(this).data('id');

                swal({
                    text: "Are you sure you want to delete this category?",
                    type: "warning",
                    confirmButtonText: "Yes",
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/category/delete/',
                            type: 'POST',
                            data: {
                                id: categoryId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                if (data.success) {
                                    debugger
                                    $('#category-' + categoryId).remove();
                                    swal('Success', 'Category deleted successfully!', 'success');
                                } else {
                                    swal('Error!', data.message, 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                swal('Error!', 'An error occurred while deleting the category. Please try again.', 'error');
                                console.error('Error:', error);
                            }
                        });
                    } else if (result.dismiss === 'cancel') {
                        swal('Cancelled', 'You stayed here :)', 'error');
                    }
                });
            });
        });
    </script>

@endsection
