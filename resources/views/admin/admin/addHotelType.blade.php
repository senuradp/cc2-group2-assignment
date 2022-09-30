@extends('admin.admin.includes.dash')
@section('title', 'Home')

@section('content')
    <!-- Content -->
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                {{-- <h3>Hotel Profile {!! empty($profile) ? '<span class="badge badge-danger">Not created</span>': ($profile->registration_fee_status==0 ? '<span class="badge badge-warning">Not paid</span>' : (empty($profile->approved_by) ? '<span class="badge badge-warning">Not approved</span>': '<span class="badge badge-success">Approved</span>')) !!}</h3> --}}
                <h3>Add Hotel Type</h3>
            </div>
        </div>

        {{-- basic details --}}
        <div class="row">
            <div class="col-lg-!2 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Add Hotel Type</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <form action="/site-admin/add-hotel-type" method="POST" >
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name">Hotel Type </label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                value="{{ old('name') }}"
                                                placeholder="Hotel Type" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <button type="submit" class="btn btn-primary">Create</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- ./ Content -->

@endsection
@section('scripts')

<script>
    toastr.options = {
        timeOut: 3000,
        progressBar: true,
        showMethod: "slideDown",
        hideMethod: "slideUp",
        showDuration: 200,
        hideDuration: 200
    };

    $(document).ready(function() {
                        @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        toastr.error('{{ $error }}');
                                    @endforeach
                        @endif

                        @if(session()->has('error'))

                                toastr.error('{{ session()->get('error') }}');

                        @endif

                        @if(session()->has('success'))
                            toastr.success('{{ session()->get('success') }}');
                        @endif

    });
</script>
@endsection
