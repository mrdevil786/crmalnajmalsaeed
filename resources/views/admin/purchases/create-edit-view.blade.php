@extends('admin.layout.main')

@section('admin-page-title', isset($purchase) ? 'Edit Purchase' : 'Create Purchase')

@section('admin-main-section')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ isset($purchase) ? 'Edit Purchase' : 'Create Purchase' }}</h1>
            <a href="{{ route('admin.purchases.index') }}" class="btn btn-danger">
                <i class="fa fa-arrow-circle-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Purchase Details</h3>
                </div>
                <div class="card-body">
                    <form id="purchase-form"
                        action="{{ isset($purchase) ? route('admin.purchases.update', $purchase->id) : route('admin.purchases.store') }}"
                        method="POST">
                        @csrf
                        @isset($purchase)
                            @method('PUT')
                        @endisset

                        <div class="form-row">
                            <!-- Supplier -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="supplier_id">Supplier</label>
                                <select class="form-select form-control" name="supplier_id" id="supplier_id"
                                    {{ isset($purchase) && !$isEdit ? 'disabled' : 'required' }}>
                                    <option value="" selected disabled>Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ isset($purchase) && $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Purchase Date -->
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="purchase_date">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date" id="purchase_date"
                                    value="{{ isset($purchase) ? \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') : '' }}"
                                    {{ isset($purchase) && !$isEdit ? 'disabled' : 'required' }}>
                                @error('purchase_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="due_date">Due Date</label>
                                <input type="date" class="form-control" name="due_date" id="due_date"
                                    value="{{ isset($purchase) && $purchase->due_date ? \Carbon\Carbon::parse($purchase->due_date)->format('Y-m-d') : '' }}"
                                    {{ isset($purchase) && !$isEdit ? 'disabled' : '' }}>
                                @error('due_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Purchase Items Table -->
                        <div class="table-responsive mt-4">
                            <table class="table border text-nowrap text-md-nowrap table-bordered" id="items-table">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="col-5">Product</th>
                                        <th class="col-2">Quantity</th>
                                        <th class="col-2">Price</th>
                                        <th class="col-2">Total</th>
                                        @if (!isset($purchase) || $isEdit)
                                            <th class="col-1">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($purchase))
                                        @foreach ($purchase->items as $item)
                                            <tr>
                                                <td class="col-5">
                                                    <select name="items[{{ $loop->index }}][product_id]"
                                                        class="form-control product-select"
                                                        {{ !$isEdit ? 'disabled' : 'required' }}>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                {{ $item->product_id == $product->id ? 'selected' : '' }}
                                                                data-price="{{ $product->price }}">
                                                                {{ $product->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="col-2">
                                                    <input type="number" name="items[{{ $loop->index }}][quantity]"
                                                        class="form-control quantity" value="{{ $item->quantity }}"
                                                        min="1" step="0.01"
                                                        {{ !$isEdit ? 'disabled' : 'required' }}>
                                                </td>
                                                <td class="col-2">
                                                    <input type="number" name="items[{{ $loop->index }}][price]"
                                                        class="form-control price" value="{{ $item->price }}"
                                                        min="0" step="0.01"
                                                        {{ !$isEdit ? 'disabled' : 'required' }}>
                                                </td>
                                                <td class="col-2">
                                                    <span class="item-total">{{ number_format($item->total, 2) }}</span>
                                                </td>
                                                @if ($isEdit)
                                                    <td class="col-1 text-center">
                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-pill btn-sm delete-row">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="{{ !isset($purchase) || $isEdit ? '4' : '3' }}" class="text-right">
                                            <strong>Subtotal:</strong>
                                        </td>
                                        <td>
                                            <span
                                                id="subtotal">{{ isset($purchase) ? number_format($purchase->subtotal, 2) : '0.00' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="{{ !isset($purchase) || $isEdit ? '3' : '2' }}" class="text-right">
                                            <strong>Tax (%):</strong>
                                        </td>
                                        <td>
                                            <input type="number" name="tax_percentage" id="tax_percentage"
                                                class="form-control"
                                                value="{{ isset($purchase) ? $purchase->tax_percentage : '15' }}"
                                                min="0" step="0.01"
                                                {{ isset($purchase) && !$isEdit ? 'disabled' : 'required' }}>
                                        </td>
                                        <td>
                                            <span
                                                id="tax-amount">{{ isset($purchase) ? number_format($purchase->tax_amount, 2) : '0.00' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="{{ !isset($purchase) || $isEdit ? '3' : '2' }}" class="text-right">
                                            <strong>Discount:</strong>
                                        </td>
                                        <td>
                                            <input type="number" name="discount" id="discount" class="form-control"
                                                value="{{ isset($purchase) ? $purchase->discount : '0' }}" min="0"
                                                step="0.01"
                                                {{ isset($purchase) && !$isEdit ? 'disabled' : 'required' }}>
                                        </td>
                                        <td>
                                            <span
                                                id="total">{{ isset($purchase) ? number_format($purchase->total, 2) : '0.00' }}</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if (!isset($purchase) || $isEdit)
                            <div class="mb-3">
                                <button type="button" class="btn btn-info" id="add-item">
                                    <i class="fa fa-plus"></i> Add Item
                                </button>
                            </div>

                            <center>
                                <button type="submit" class="btn btn-success">
                                    {{ isset($purchase) ? 'Update Purchase' : 'Create Purchase' }}
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
    <script src="{{ asset('../assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.product-select').select2();

            // Add new item row
            $('#add-item').click(function() {
                const rowCount = $('#items-table tbody tr').length;
                const newRow = `
                    <tr>
                        <td class="col-5">
                            <select name="items[${rowCount}][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="col-2">
                            <input type="number" name="items[${rowCount}][quantity]" class="form-control quantity"
                                value="1" min="1" step="0.01" required>
                        </td>
                        <td class="col-2">
                            <input type="number" name="items[${rowCount}][price]" class="form-control price"
                                min="0" step="0.01" required>
                        </td>
                        <td class="col-2">
                            <span class="item-total">0.00</span>
                        </td>
                        <td class="col-1 text-center">
                            <button type="button" class="btn btn-outline-danger btn-pill btn-sm delete-row">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#items-table tbody').append(newRow);
                $('#items-table tbody tr:last .product-select').select2();
            });

            // Delete row
            $(document).on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
                calculateTotals();
            });

            // Product selection change
            $(document).on('change', '.product-select', function() {
                const price = $(this).find(':selected').data('price');
                $(this).closest('tr').find('.price').val(price).trigger('change');
            });

            // Recalculate on input change
            $(document).on('change keyup', '.quantity, .price, #tax_percentage, #discount', function() {
                calculateTotals();
            });

            function calculateTotals() {
                let subtotal = 0;
                $('#items-table tbody tr').each(function() {
                    const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    const price = parseFloat($(this).find('.price').val()) || 0;
                    const total = quantity * price;
                    $(this).find('.item-total').text(total.toFixed(2));
                    subtotal += total;
                });

                const taxPercentage = parseFloat($('#tax_percentage').val()) || 0;
                const discount = parseFloat($('#discount').val()) || 0;
                const taxAmount = (subtotal * taxPercentage) / 100;
                const total = subtotal + taxAmount - discount;

                $('#subtotal').text(subtotal.toFixed(2));
                $('#tax-amount').text(taxAmount.toFixed(2));
                $('#total').text(total.toFixed(2));
            }

            // Add initial row if empty
            if ($('#items-table tbody tr').length === 0) {
                $('#add-item').click();
            }

            // Initial calculation
            calculateTotals();
        });
    </script>
@endsection
