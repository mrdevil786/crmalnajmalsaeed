@extends('admin.layout.main')

@section('admin-page-title', isset($expenditure) ? 'Edit Expenditure' : 'Create Expenditure')

@section('admin-main-section')

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ isset($expenditure) ? 'Edit Expenditure' : 'Create Expenditure' }}</h1>
            <a href="{{ route('admin.expenditures.index') }}" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i>
                Back</a>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Expenditure Details</h3>
                </div>
                <div class="card-body">
                    <form id="expenditure-form"
                        action="{{ isset($expenditure) ? route('admin.expenditures.update', $expenditure->id) : route('admin.expenditures.store') }}"
                        method="POST">
                        @csrf
                        @isset($expenditure)
                            @method('PUT')
                        @endisset

                        <div class="form-row">
                            <!-- Description -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="description">Description</label>
                                <input type="text" class="form-control" name="description" id="description"
                                    value="{{ isset($expenditure) ? $expenditure->description : '' }}"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : 'required' }}>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="amount">Amount</label>
                                <input type="number" class="form-control" name="amount" id="amount"
                                    value="{{ isset($expenditure) ? $expenditure->amount : '' }}"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : 'required' }} step="0.01">
                                @error('amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category (Dropdown) -->
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="category">Category</label>
                                <select class="form-select form-control" name="category" id="category"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : 'required' }}>
                                    <option value="" selected disabled>Select Category</option>
                                    <option value="Rent"
                                        {{ isset($expenditure) && $expenditure->category == 'Rent' ? 'selected' : '' }}>
                                        Rent</option>
                                    <option value="Salaries"
                                        {{ isset($expenditure) && $expenditure->category == 'Salaries' ? 'selected' : '' }}>
                                        Salaries</option>
                                    <option value="Utilities"
                                        {{ isset($expenditure) && $expenditure->category == 'Utilities' ? 'selected' : '' }}>
                                        Utilities</option>
                                    <option value="Office Supplies"
                                        {{ isset($expenditure) && $expenditure->category == 'Office Supplies' ? 'selected' : '' }}>
                                        Office Supplies</option>
                                </select>
                                @error('category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- Payment Method (Dropdown) -->
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="payment_method">Payment Method</label>
                                <select class="form-select form-control" name="payment_method" id="payment_method"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : 'required' }}>
                                    <option value="" selected disabled>Select Payment Method</option>
                                    <option value="Cash"
                                        {{ isset($expenditure) && $expenditure->payment_method == 'Cash' ? 'selected' : '' }}>
                                        Cash</option>
                                    <option value="Bank Transfer"
                                        {{ isset($expenditure) && $expenditure->payment_method == 'Bank Transfer' ? 'selected' : '' }}>
                                        Bank Transfer</option>
                                    <option value="Cheque"
                                        {{ isset($expenditure) && $expenditure->payment_method == 'Cheque' ? 'selected' : '' }}>
                                        Cheque</option>
                                    <option value="Credit Card"
                                        {{ isset($expenditure) && $expenditure->payment_method == 'Credit Card' ? 'selected' : '' }}>
                                        Credit Card</option>
                                </select>
                                @error('payment_method')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date -->
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="date">Date</label>
                                <input type="datetime-local" class="form-control" name="date" id="date"
                                    value="{{ isset($expenditure) ? \Carbon\Carbon::parse($expenditure->date)->format('Y-m-d\TH:i') : '' }}"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : 'required' }}>
                                @error('date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Invoice Number -->
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="invoice_number">Invoice Number</label>
                                <input type="text" class="form-control" name="invoice_number" id="invoice_number"
                                    value="{{ isset($expenditure) ? $expenditure->invoice_number : '' }}"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : 'required' }}>
                                @error('invoice_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- User ID (Hidden) -->
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        </div>

                        <!-- Submit Button -->
                        @if (!isset($expenditure) || $isEdit)
                            <center>
                                <button type="submit" class="btn btn-success">
                                    {{ isset($expenditure) ? 'Update Expenditure' : 'Create Expenditure' }}
                                </button>
                            </center>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
