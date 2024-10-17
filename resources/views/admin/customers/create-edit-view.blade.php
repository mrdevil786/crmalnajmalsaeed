@extends('admin.layout.main')

@section('admin-page-title', $isEdit ? 'Edit Customer' : 'View Customer')

@section('admin-main-section')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ $isEdit ? 'Edit Customer' : 'View Customer' }}</h1>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i>
                Back</a>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $isEdit ? 'Edit Customer' : 'View Customer' }}</h3>
                </div>
                <div class="card-body">
                    @if ($isEdit)
                        <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
                            @method('PUT')
                            @csrf
                    @endif

                    <div class="form-row">
                        <div class="col-xl-6 mb-3">
                            <label class="form-label mt-0" for="name">Customer Name</label>
                            @if ($isEdit)
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $customer->name) }}">
                            @else
                                <p class="form-control">{{ $customer->name }}</p>
                            @endif
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-6 mb-3">
                            <label class="form-label mt-0" for="email">Email</label>
                            @if ($isEdit)
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $customer->email) }}">
                            @else
                                <p class="form-control">{{ $customer->email }}</p>
                            @endif
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-6 mb-3">
                            <label class="form-label mt-0" for="tax_number">Tax Number</label>
                            @if ($isEdit)
                                <input type="text" class="form-control" id="tax_number" name="tax_number"
                                    value="{{ old('tax_number', $customer->tax_number) }}">
                            @else
                                <p class="form-control">{{ $customer->tax_number }}</p>
                            @endif
                            @error('tax_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-6 mb-3">
                            <label class="form-label mt-0" for="pincode">Pincode</label>
                            @if ($isEdit)
                                <input type="text" class="form-control" id="pincode" name="pincode"
                                    value="{{ old('pincode', $customer->pincode) }}">
                            @else
                                <p class="form-control">{{ $customer->pincode }}</p>
                            @endif
                            @error('pincode')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-12 mb-3">
                            <label class="form-label mt-0" for="address">Address</label>
                            @if ($isEdit)
                                <textarea class="form-control" id="address" name="address">{{ old('address', $customer->address) }}</textarea>
                            @else
                                <p class="form-control">{{ $customer->address }}</p>
                            @endif
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    @if ($isEdit)
                        <center><button class="btn btn-primary" type="submit">Update Customer</button></center>
                    @endif

                    @if ($isEdit)
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

@endsection

@section('custom-script')
    <!-- INPUT MASK JS-->
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

    <!-- FORM VALIDATION JS -->
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection
