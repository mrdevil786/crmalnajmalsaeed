@extends('admin.layouts.app')

@section('title', isset($supplier) ? ($isEdit ?? false ? 'Edit Supplier' : 'View Supplier') : 'Create Supplier')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ isset($supplier) ? ($isEdit ?? false ? 'Edit Supplier' : 'View Supplier') : 'Create Supplier' }}
                    </h3>
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
                    @else
                        {{-- Create/Edit Mode --}}
                        <form method="POST" 
                              action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier->id) : route('admin.suppliers.store') }}">
                            @csrf
                            @if(isset($supplier))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $supplier->name ?? '') }}" 
                                               required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $supplier->email ?? '') }}" 
                                               required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input type="text" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', $supplier->phone ?? '') }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="tax_number">Tax Number</label>
                                        <input type="text" 
                                               class="form-control @error('tax_number') is-invalid @enderror" 
                                               id="tax_number" 
                                               name="tax_number" 
                                               value="{{ old('tax_number', $supplier->tax_number ?? '') }}">
                                        @error('tax_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" 
                                                  name="address" 
                                                  rows="3">{{ old('address', $supplier->address ?? '') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-footer mt-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($supplier) ? 'Update' : 'Create' }} Supplier
                                </button>
                                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-light">Cancel</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            @if(isset($supplier) && !($isEdit ?? false) && $supplier->purchases->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Purchase History</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Purchase Number</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->purchases as $purchase)
                                        <tr>
                                            <td>{{ $purchase->purchase_number }}</td>
                                            <td>{{ $purchase->purchase_date }}</td>
                                            <td>{{ $purchase->total }}</td>
                                            <td>
                                                <span class="badge bg-{{ $purchase->status === 'completed' ? 'success' : ($purchase->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($purchase->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.purchases.view', $purchase->id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 