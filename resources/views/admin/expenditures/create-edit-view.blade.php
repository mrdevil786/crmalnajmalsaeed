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
                        method="POST" enctype="multipart/form-data">
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

                            <!-- Reference Number -->
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="reference_number">Reference Number</label>
                                <input type="text" class="form-control" name="reference_number" id="reference_number"
                                    value="{{ isset($expenditure) ? $expenditure->reference_number : '' }}"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : '' }}>
                                @error('reference_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
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
                                    <option value="Marketing"
                                        {{ isset($expenditure) && $expenditure->category == 'Marketing' ? 'selected' : '' }}>
                                        Marketing</option>
                                    <option value="Travel"
                                        {{ isset($expenditure) && $expenditure->category == 'Travel' ? 'selected' : '' }}>
                                        Travel</option>
                                    <option value="Maintenance"
                                        {{ isset($expenditure) && $expenditure->category == 'Maintenance' ? 'selected' : '' }}>
                                        Maintenance</option>
                                    <option value="Others"
                                        {{ isset($expenditure) && $expenditure->category == 'Others' ? 'selected' : '' }}>
                                        Others</option>
                                </select>
                                @error('category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Method -->
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
                                    <option value="Digital Wallet"
                                        {{ isset($expenditure) && $expenditure->payment_method == 'Digital Wallet' ? 'selected' : '' }}>
                                        Digital Wallet</option>
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
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : '' }}>
                                @error('invoice_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Vendor Name -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="vendor_name">Vendor Name</label>
                                <input type="text" class="form-control" name="vendor_name" id="vendor_name"
                                    value="{{ isset($expenditure) ? $expenditure->vendor_name : '' }}"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : '' }}>
                                @error('vendor_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Vendor Contact -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="vendor_contact">Vendor Contact</label>
                                <input type="text" class="form-control" name="vendor_contact" id="vendor_contact"
                                    value="{{ isset($expenditure) ? $expenditure->vendor_contact : '' }}"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : '' }}>
                                @error('vendor_contact')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="notes">Notes</label>
                                <textarea class="form-control" name="notes" id="notes" rows="3"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : '' }}>{{ isset($expenditure) ? $expenditure->notes : '' }}</textarea>
                                @error('notes')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Receipt Image -->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="receipt_image">Receipt Image</label>
                                <input type="file" class="form-control" name="receipt_image" id="receipt_image"
                                    {{ isset($expenditure) && !$isEdit ? 'disabled' : '' }}
                                    accept="image/*">
                                @if(isset($expenditure) && $expenditure->receipt_image)
                                    <div class="mt-2">
                                        <a href="{{ asset($expenditure->receipt_image) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> View Receipt
                                        </a>
                                    </div>
                                @endif
                                @error('receipt_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(isset($expenditure) && auth()->user()->user_role == 1)
                                <!-- Status -->
                                <div class="col-lg-4 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select form-control" name="status" id="status">
                                        <option value="pending" {{ $expenditure->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $expenditure->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $expenditure->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>

                                <!-- Rejection Reason -->
                                <div class="col-lg-8 mb-3" id="rejection-reason-container" style="{{ $expenditure->status != 'rejected' ? 'display: none;' : '' }}">
                                    <label class="form-label" for="rejection_reason">Rejection Reason</label>
                                    <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="2">{{ $expenditure->rejection_reason }}</textarea>
                                </div>
                            @endif

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

@section('custom-script')
<script>
    $(document).ready(function() {
        $('#status').change(function() {
            if ($(this).val() === 'rejected') {
                $('#rejection-reason-container').show();
            } else {
                $('#rejection-reason-container').hide();
            }
        });
    });
</script>
@endsection
