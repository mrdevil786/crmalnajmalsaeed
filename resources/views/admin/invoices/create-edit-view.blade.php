@extends('admin.layout.main')

@section('admin-page-title', 'Create Invoices')

@section('admin-main-section')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">Create Invoice</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Invoice Details</h3>
                </div>
                <div class="card-body">

                    <!-- Invoice Form -->
                    <form id="invoice-form" action="{{ route('admin.invoices.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <!-- Customer Section -->
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

                            <!-- Invoice Type -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="type">Invoice Type</label>
                                <select class="form-select form-control" name="type" id="type" required>
                                    <option value="invoice">Invoice</option>
                                    <option value="quote">Quote</option>
                                </select>
                            </div>

                            <!-- Due Date -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="due_date">Due Date</label>
                                <input type="date" class="form-control" name="due_date" id="due_date">
                            </div>

                            <!-- VAT Percentage -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="vat_percentage">VAT Percentage</label>
                                <input type="number" class="form-control" name="vat_percentage" id="vat_percentage"
                                    value="15" step="0.01" required>
                            </div>

                            <!-- Discount -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="discount">Discount Percentage</label>
                                <input type="number" class="form-control" name="discount" id="discount" value="0"
                                    step="0.01">
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- Items Section -->
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
                                                <input type="number" class="form-control" name="items[0][quantity]"
                                                    value="1" required>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="price">Price</label>
                                                <input type="number" class="form-control" name="items[0][price]" readonly
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Button to Add New Items -->
                                <button type="button" id="add-item" class="btn btn-primary">Add Item</button>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="form-row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="notes">Notes</label>
                                <textarea class="form-control" name="notes" id="notes"></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <center>
                            <button type="submit" class="btn btn-success">Create Invoice</button>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

@endsection

@section('custom-script')
    <script>
        $(document).ready(function() {
            // Update price on product selection for the permanent item
            $('select[name="items[0][product_id]"]').change(function() {
                const selectedOption = $(this).find('option:selected');
                const priceInput = $(this).closest('.item').find('input[name="items[0][price]"]');
                priceInput.val(selectedOption.data('price'));
            });

            // Add new item (with remove button)
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
                            <input type="number" class="form-control" name="items[${itemCount}][quantity]" value="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="price">Price</label>
                            <input type="number" class="form-control" name="items[${itemCount}][price]" readonly required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>`);

                $('#items').append(itemDiv);

                // Update price when the new product is selected
                itemDiv.find('select[name^="items"][name$="[product_id]"]').change(function() {
                    const selectedOption = $(this).find('option:selected');
                    const priceInput = $(this).closest('.item').find('input[name$="[price]"]');
                    priceInput.val(selectedOption.data('price'));
                });

                // Remove item
                itemDiv.find('.remove-item').click(function() {
                    itemDiv.remove();
                });
            });

            // Trigger price update for the first item on load
            $('select[name="items[0][product_id]"]').trigger('change');
        });
    </script>

    <!-- FILE UPLOADES JS -->
    <script src="{{ asset('../assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('../assets/plugins/fileuploads/js/file-upload.js') }}"></script>

    <!-- INPUT MASK JS-->
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

    <!-- FORMVALIDATION JS -->
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection
