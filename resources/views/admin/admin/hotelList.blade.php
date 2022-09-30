@extends('admin.admin.includes.dash')
@section('title', 'Hotel-List')

@section('content')
    <!-- Content -->
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h3>Hotel List</h3>
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
                                        <th>Name</th>
                                        <th>Legal Name</th>
                                        <th>Contact</th>
                                        <th>Address</th>
                                        <th>Admin Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hotels as $hotel)
                                        <tr>
                                            {{-- {{ $row['id'] }} --}}
                                            <td>{{ $hotel->name }}</td>
                                            <td>{{ $hotel->leagal_name }}</td>
                                            <td>{{ isset($hotel->contacts) ? $hotel->contacts[0] : 'N/A' }}</td>
                                            <td>{{ $hotel->address }}</td>
                                            <td>{{ $hotel->users->first()->email }}</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)"
                                                    onclick="viewProfile({{ $hotel->id }})"
                                                    class="btn btn-outline-success btn-sm btn-floating mr-2 view-btn" data-original-title="View">
                                                    <i class="ti-eye"></i>
                                                </a>
                                            </td>
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
            $('#myTable').DataTable();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function viewProfile(id){
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

