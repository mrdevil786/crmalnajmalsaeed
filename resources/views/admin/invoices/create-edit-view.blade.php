@extends('admin.layout.main')

@section('admin-page-title', isset($invoice) ? 'Edit Invoice' : 'Create Invoice')

@section('admin-main-section')

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ isset($invoice) ? 'Edit Invoice' : 'Create Invoice' }}</h1>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i>
                Back</a>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Invoice Details</h3>
                </div>
                <div class="card-body">
                    <form id="invoice-form"
                        action="{{ isset($invoice) ? route('admin.invoices.update', $invoice->id) : route('admin.invoices.store') }}"
                        method="POST">
                        @csrf
                        @isset($invoice)
                            @method('PUT')
                        @endisset

                        <div class="form-row">
                            <!-- Customer Selection -->
                            <div class="col-lg-8 mb-3">
                                <label class="form-label" for="customer_id">Customer</label>
                                <select class="form-select form-control" name="customer_id" id="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ isset($invoice) && $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- VAT and Discount -->
                            <div class="col-lg-2 mb-3">
                                <label class="form-label" for="vat_percentage">Tax (%)</label>
                                <input type="number" class="form-control" name="vat_percentage" id="vat_percentage"
                                    value="{{ isset($invoice) ? $invoice->vat_percentage : 15 }}" step="0.01" required>
                                @error('vat_percentage')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-2 mb-3">
                                <label class="form-label" for="discount">Discount (%)</label>
                                <input type="number" class="form-control" name="discount" id="discount"
                                    value="{{ isset($invoice) ? $invoice->discount : 0 }}" step="0.01">
                                @error('discount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Invoice Items Section -->
                        <div class="form-row mt-5">
                            <div class="col-lg-12 mb-3">
                                <h4>Items</h4>
                                <div id="items">
                                    @isset($invoice)
                                        @foreach ($invoice->items as $index => $item)
                                            @include('admin.includes.partials.item', [
                                                'index' => $index,
                                                'item' => $item,
                                                'products' => $products,
                                            ])
                                        @endforeach
                                    @else
                                        @include('admin.includes.partials.item', [
                                            'index' => 0,
                                            'item' => null,
                                            'products' => $products,
                                        ])
                                    @endisset
                                </div>

                                <div class="text-end">
                                    <button type="button" id="add-item" class="btn btn-primary">
                                        <i class="fe fe-plus-square"></i> Add Item
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="form-row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="notes">Notes</label>
                                <textarea class="form-control" name="notes" id="notes">{{ isset($invoice) ? $invoice->notes : '' }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <center>
                            <button type="submit" class="btn btn-success">
                                {{ isset($invoice) ? 'Update Invoice' : 'Create Invoice' }}
                            </button>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom-script')
    <script>
        $(document).ready(function() {
            $('#customer_id').select2();

            $(document).on('change', 'select[name^="items"][name$="[product_id]"]', function() {
                $(this).select2();
            });

            function updatePrice(itemDiv) {
                const quantityInput = itemDiv.find('input[name$="[quantity]"]');
                const priceInput = itemDiv.find('input[name$="[price]"]');
                const totalPriceInput = itemDiv.find('.total_price');
                const selectedProduct = itemDiv.find('select[name$="[product_id]"] option:selected');
                const productPrice = parseFloat(selectedProduct.data('price')) || 0;
                const quantity = parseFloat(quantityInput.val()) || 1;

                priceInput.val(productPrice.toFixed(2));
                totalPriceInput.val((productPrice * quantity).toFixed(2));
            }

            $(document).on('change', 'select[name^="items"][name$="[product_id]"]', function() {
                const itemDiv = $(this).closest('.item');
                updatePrice(itemDiv);
            });

            $(document).on('input', 'input[name$="[quantity]"]', function() {
                const itemDiv = $(this).closest('.item');
                updatePrice(itemDiv);
            });

            $('#add-item').click(function() {
                const itemCount = $('.item').length;
                const itemDiv = $(`
                    <div class="item mb-3">
                        <div class="row align-items-end">
                            <div class="col-xl-4 col-md-6 mb-3">
                                <label class="form-label" for="product_id">Product</label>
                                <select class="form-select form-control" name="items[${itemCount}][product_id]" required>
                                    <option value="" selected disabled>Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-md-6 mb-3">
                                <label class="form-label" for="quantity">Quantity</label>
                                <input type="number" class="form-control" name="items[${itemCount}][quantity]" value="1" step="0.01" required>
                            </div>
                            <input type="hidden" class="form-control" name="items[${itemCount}][price]" required>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <label class="form-label" for="total_price">Total Price</label>
                                <input type="text" class="form-control total_price" readonly>
                            </div>
                            <div class="col-xl-1 col-md-6 d-flex justify-content-center align-items-center mb-3">
                                <button type="button" class="btn btn-danger remove-item">
                                    <i class="fe fe-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);

                $('#items').append(itemDiv);
                itemDiv.find('select[name^="items"][name$="[product_id]"]').select2();
                itemDiv.find('select[name^="items"][name$="[product_id]"]').change(function() {
                    updatePrice(itemDiv);
                });
                itemDiv.find('input[name$="[quantity]"]').on('input', function() {
                    updatePrice(itemDiv);
                });
                itemDiv.find('.remove-item').click(function() {
                    itemDiv.remove();
                });
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item').remove();
            });

            $('.item').each(function() {
                updatePrice($(this));
            });
        });
    </script>

    <script src="{{ asset('../assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('../assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection
