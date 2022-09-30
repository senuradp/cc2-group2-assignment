@extends('admin.admin.includes.dash')
@section('title', 'Tourist Bookings List')

@section('content')
    <!-- Content -->
    {{-- <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h3>Tourist Bookings List</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-!2 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="btn btn-success">Export</div>
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Package Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id}}</td>
                                            <td>{{ $booking->tourist->first_name}}</td>
                                            <td>{{ $booking->tourist->last_name}}</td>
                                            <td>{{ $booking->package->name}}</td>
                                            <td>{{ $booking->start_date}}</td>
                                            <td>{{ $booking->end_date}}</td>
                                            <td>{{ env('DEFAULT_PRICE_TYPE_ID').' '.number_format($booking->total,2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal" tabindex="-1" id="detailModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Profile Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="modal-body">


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> --}}

    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h3>Tourist List</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-!2 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            {{-- <a href="#" class="btn btn-primary ml-2" data-toggle="dropdown">
                                <i class="ti-download"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item">Download</a>
                                <a href="#" class="dropdown-item">Print</a>
                            </div> --}}
                            <table id="myTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Package Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ $booking->tourist->first_name }}</td>
                                            <td>{{ $booking->tourist->last_name }}</td>
                                            <td>{{ $booking->package->name }}</td>
                                            <td>{{ $booking->start_date }}</td>
                                            <td>{{ $booking->end_date }}</td>
                                            <td>{{ env('DEFAULT_PRICE_TYPE_ID') . ' ' . number_format($booking->total, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal" tabindex="-1" id="detailModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Profile Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="modal-body">


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function viewProfile(id) {
            $.ajax({
                type: 'POST',
                url: '/site-admin/hotels-view',
                data: {
                    'id': id,
                },
                success: function(data) {
                    $("#modal-body").html(data);
                    $('#detailModal').modal('show');
                }
            });
        }
    </script>
@endsection
