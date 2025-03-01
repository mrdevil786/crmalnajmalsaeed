@extends('admin.layout.main')

@section('admin-page-title', isset($quotation) ? 'Edit Quotation' : 'Create Quotation')

@section('admin-main-section')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ isset($quotation) ? 'Edit Quotation' : 'Create Quotation' }}</h1>
            <a href="{{ route('admin.quotations.index') }}" class="btn btn-danger">
                <i class="fa fa-arrow-circle-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quotation Details</h3>
                </div>
                <div class="card-body">
                    <form id="quotation-form"
                        action="{{ isset($quotation) ? route('admin.quotations.update', $quotation->id) : route('admin.quotations.store') }}"
                        method="POST">
                        @csrf
                        @isset($quotation)
                            @method('PUT')
                        @endisset

                        <div class="form-row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="customer_id">Customer</label>
                                <select class="form-control select2-show-search form-select" name="customer_id" id="customer_id" required>
                                    <option value="" selected disabled>Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ isset($quotation) && $quotation->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="issue_date">Issue Date</label>
                                <input type="date" class="form-control" name="issue_date" id="issue_date"
                                    value="{{ isset($quotation) ? $quotation->issue_date : '' }}" required>
                                @error('issue_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="due_date">Due Date</label>
                                <input type="date" class="form-control" name="due_date" id="due_date"
                                    value="{{ isset($quotation) && $quotation->due_date ? \Carbon\Carbon::parse($quotation->due_date)->format('Y-m-d') : '' }}">
                                @error('due_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table border text-nowrap text-md-nowrap table-bordered" id="items-table">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="col-5">Product</th>
                                        <th class="col-2">Quantity</th>
                                        <th class="col-2">Price</th>
                                        <th class="col-2">Total</th>
                                        <th class="col-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($quotation))
                                        @foreach ($quotation->items as $index => $item)
                                            <tr>
                                                <td class="col-5">
                                                    <select name="items[{{ $index }}][product_id]"
                                                        class="form-control product-select" required>
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
                                                    <input type="number" name="items[{{ $index }}][quantity]"
                                                        class="form-control quantity" value="{{ $item->quantity }}"
                                                        min="1" step="0.01" required>
                                                </td>
                                                <td class="col-2">
                                                    <input type="number" name="items[{{ $index }}][price]"
                                                        class="form-control price" value="{{ $item->price }}"
                                                        min="0" step="0.01" required>
                                                </td>
                                                <td class="col-2">
                                                    <span class="item-total">{{ number_format($item->total, 2) }}</span>
                                                </td>
                                                <td class="col-1 text-center">
                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-pill btn-sm delete-row">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <strong>Subtotal:</strong>
                                        </td>
                                        <td colspan="2">
                                            <span
                                                id="subtotal">{{ isset($quotation) ? number_format($quotation->subtotal, 2) : '0.00' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">
                                            <strong>Discount:</strong>
                                        </td>
                                        <td>
                                            <input type="number" name="discount" id="discount" class="form-control"
                                                value="{{ isset($quotation) ? $quotation->discount : '0' }}" min="0"
                                                step="0.01" required>
                                        </td>
                                        <td colspan="2">
                                            <span
                                                id="discounted-amount">{{ isset($quotation) ? number_format($quotation->discount, 2) : '0.00' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">
                                            <strong>Tax (%):</strong>
                                        </td>
                                        <td>
                                            <input type="number" name="vat_percentage" id="tax_percentage"
                                                class="form-control"
                                                value="{{ isset($quotation) ? $quotation->vat_percentage : '15' }}"
                                                min="0" step="0.01" required>
                                        </td>
                                        <td colspan="2">
                                            <span
                                                id="tax-amount">{{ isset($quotation) ? number_format($quotation->vat_amount, 2) : '0.00' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <strong>Total:</strong>
                                        </td>
                                        <td colspan="2">
                                            <span
                                                id="total">{{ isset($quotation) ? number_format($quotation->total, 2) : '0.00' }}</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-info" id="add-item">
                                <i class="fa fa-plus"></i> Add Item
                            </button>
                        </div>

                        <div class="form-row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="notes">Notes</label>
                                <textarea class="form-control" name="notes" id="notes">{{ isset($quotation) ? $quotation->notes : '' }}</textarea>
                            </div>
                        </div>

                        <center>
                            <button type="submit" class="btn btn-success">
                                {{ isset($quotation) ? 'Update Quotation' : 'Create Quotation' }}
                            </button>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')
    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.product-select').select2();

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

            $(document).on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
                calculateTotals();
            });

            $(document).on('change', '.product-select', function() {
                const price = $(this).find(':selected').data('price');
                $(this).closest('tr').find('.price').val(price).trigger('change');
            });

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

                const discountedSubtotal = subtotal - discount;

                const taxAmount = (discountedSubtotal * taxPercentage) / 100;

                const total = discountedSubtotal + taxAmount;

                $('#subtotal').text(subtotal.toFixed(2));
                $('#discounted-amount').text(discount.toFixed(2));
                $('#tax-amount').text(taxAmount.toFixed(2));
                $('#total').text(total.toFixed(2));
            }

            if ($('#items-table tbody tr').length === 0) {
                $('#add-item').click();
            }

            calculateTotals();
        });
    </script>
@endsection
