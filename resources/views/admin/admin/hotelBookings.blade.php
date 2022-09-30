@extends('admin.admin.includes.dash')
@section('title', 'Tourist Bookings List')

@section('content')
    <!-- Content -->
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h3>Hotel Bookings List</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-!2 col-md-12">
                <div class="card">
                    <div class="card-body">
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
                                            {{-- {{ $row['id'] }} --}}
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
                            {{-- {{ $data->links() }} --}}
                        </div>
                    </div>
                    {{-- modal start --}}
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

                                    {{-- <p><strong>Profile ID:</strong> <span id="hotel-id"></span></p>
                                    <p><strong>Hotel Name:</strong> <span id="hotel-name"></span></p>
                                    <p><strong>Address   :</strong> <span id="hotel-add"></span></p> --}}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- modal end --}}
                </div>
            </div>
        </div>

    </div>
    <!-- ./ Content -->

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
