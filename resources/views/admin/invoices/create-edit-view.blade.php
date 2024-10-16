@extends('admin.layout.main')

@section('admin-page-title', 'Create Invoices')

@section('admin-main-section')

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">Create Invoice</h1>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Invoice Details</h3>
                </div>
                <div class="card-body">
                    <form id="invoice-form" action="{{ route('admin.invoices.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="customer_id">Customer</label>
                                <select class="form-select form-control" name="customer_id" id="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="vat_percentage">VAT Percentage</label>
                                <input type="number" class="form-control" name="vat_percentage" id="vat_percentage"
                                    value="15" step="0.01" required>
                                @error('vat_percentage')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label class="form-label" for="discount">Discount Percentage</label>
                                <input type="number" class="form-control" name="discount" id="discount" value="0"
                                    step="0.01">
                                @error('discount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mt-5">
                            <div class="col-lg-12 mb-3">
                                <h4>Items</h4>
                                <div id="items">
                                    <div class="item mb-3">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="product_id">Product</label>
                                                <select class="form-select form-control" name="items[0][product_id]"
                                                    required>
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            data-price="{{ $product->price }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="quantity">Quantity</label>
                                                <input type="text" class="form-control" name="items[0][quantity]"
                                                    value="1" pattern="^\d*\.?\d+$" required>
                                            </div>

                                            <input type="hidden" class="form-control" name="items[0][price]" required>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="total_price">Total Price</label>
                                                <input type="text" class="form-control" id="total_price" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" id="add-item" class="btn btn-primary"><i
                                            class="fe fe-plus-square"></i> Add Item</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="notes">Notes</label>
                                <textarea class="form-control" name="notes" id="notes"></textarea>
                            </div>
                        </div>

                        <center>
                            <button type="submit" class="btn btn-success">Create Invoice</button>
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
            function updatePrice(itemDiv) {
                const quantityInput = itemDiv.find('input[name$="[quantity]"]');
                const priceInput = itemDiv.find('input[name$="[price]"]');
                const totalPriceInput = itemDiv.find('#total_price');
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
                const itemDiv = $(`<div class="item mb-3">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="product_id">Product</label>
                            <select class="form-select form-control" name="items[${itemCount}][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="quantity">Quantity</label>
                            <input type="text" class="form-control" name="items[${itemCount}][quantity]" value="1" pattern="^\d*\.?\d+$" required>
                        </div>
                        <input type="hidden" class="form-control" name="items[${itemCount}][price]" required>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="total_price">Total Price</label>
                            <input type="text" class="form-control" id="total_price" readonly>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger remove-item"><i class="fe fe-trash"></i></button>
                </div>`);

                $('#items').append(itemDiv);

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

            $('select[name="items[0][product_id]"]').trigger('change');
        });
    </script>

    <script src="{{ asset('../assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('../assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection
