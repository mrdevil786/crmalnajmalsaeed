@extends('admin.layout.main')

@section('admin-page-title', isset($supplier) ? 'Edit Supplier' : 'Create Supplier')

@section('admin-main-section')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ isset($supplier) ? 'Edit Supplier' : 'Create Supplier' }}</h1>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-danger">
                <i class="fa fa-arrow-circle-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Supplier Details</h3>
                </div>
                <div class="card-body">
                    @if(isset($supplier) && !($isEdit ?? false))
                        {{-- View Mode --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <p class="form-control-static">{{ $supplier->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <p class="form-control-static">{{ $supplier->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <p class="form-control-static">{{ $supplier->phone }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tax Number</label>
                                    <p class="form-control-static">{{ $supplier->tax_number }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <p class="form-control-static">{{ $supplier->address }}</p>
                                </div>
                            </div>
                        </div>

                        @if($supplier->purchases->count() > 0)
                            <div class="table-responsive mt-4">
                                <h4>Purchase History</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Purchase Number</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supplier->purchases as $purchase)
                                            <tr>
                                                <td>{{ $purchase->purchase_number }}</td>
                                                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') }}</td>
                                                <td>{{ number_format($purchase->total, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $purchase->status === 'completed' ? 'success' : ($purchase->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($purchase->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.purchases.view', $purchase->id) }}" 
                                                       class="btn btn-info btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @else
                        {{-- Create/Edit Mode --}}
                        <form method="POST" 
                              action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier->id) : route('admin.suppliers.store') }}">
                            @csrf
                            @if(isset($supplier))
                                @method('PUT')
                            @endif

                            <div class="form-row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label" for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ old('name', $supplier->name ?? '') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        value="{{ old('email', $supplier->email ?? '') }}" required>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label class="form-label" for="phone">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                        value="{{ old('phone', $supplier->phone ?? '') }}">
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label class="form-label" for="tax_number">Tax Number</label>
                                    <input type="text" class="form-control" name="tax_number" id="tax_number"
                                        value="{{ old('tax_number', $supplier->tax_number ?? '') }}">
                                    @error('tax_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="address">Address</label>
                                    <textarea class="form-control" name="address" id="address" rows="3">{{ old('address', $supplier->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <center>
                                <button type="submit" class="btn btn-success">
                                    {{ isset($supplier) ? 'Update Supplier' : 'Create Supplier' }}
                                </button>
                            </center>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 