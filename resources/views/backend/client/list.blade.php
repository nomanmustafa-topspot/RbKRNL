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
                            <div class="d-flex justify-content-between">
                                <h2 class="card-title">Clients List</h2>
                                <a href="{{ URL::to('/add-client') }}" class="btn btn-primary btn-sm add-new-version">
                                    Add New
                                </a>
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table admin-factors-list" >
                                <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Designation</th>
                                        <th>Website</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)

                                        <tr>
                                            <td>{{ $client['id'] }}</td>
                                            <td>{{ $client['name'] }}</td>
                                            <td>{{ $client['email'] }}</td>
                                            <td>{{ $client['designation'] }}</td>
                                            <td>{{ $client['website'] }}</td>
                                            <td>{{ $client['created_at']->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

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
            $('.admin-factors-list').DataTable();
        });
    </script>
@endsection
