<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Create Invoice</h1>
        <form id="invoice-form" action="{{ route('admin.invoices.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="customer_id">Customer:</label>
                <select class="form-control" name="customer_id" id="customer_id" required>
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                @error('customer_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Invoice Type:</label>
                <select class="form-control" name="type" id="type" required>
                    <option value="invoice">Invoice</option>
                    <option value="quote">Quote</option>
                </select>
            </div>

            <div class="form-group">
                <label for="issue_date">Issue Date:</label>
                <input type="date" class="form-control" name="issue_date" id="issue_date" required>
            </div>

            <div class="form-group" id="due-date-group">
                <label for="due_date">Due Date:</label>
                <input type="date" class="form-control" name="due_date" id="due_date">
            </div>

            <div class="form-group">
                <label for="vat_percentage">VAT Percentage:</label>
                <input type="number" class="form-control" name="vat_percentage" id="vat_percentage" value="15"
                    step="0.01" required>
            </div>

            <div class="form-group">
                <label for="discount">Discount:</label>
                <input type="number" class="form-control" name="discount" id="discount" value="0" step="0.01">
            </div>

            <h2>Items</h2>
            <div id="items" class="mb-3">
                <div class="item mb-3">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="product_id">Product:</label>
                            <select class="form-control" name="items[0][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="quantity">Quantity:</label>
                            <input type="number" class="form-control" name="items[0][quantity]" value="1"
                                required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="price">Price:</label>
                            <input type="number" class="form-control" name="items[0][price]" required readonly>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
            <button type="button" id="add-item" class="btn btn-primary">Add Item</button>

            <div class="form-group mt-3">
                <label for="notes">Notes:</label>
                <textarea class="form-control" name="notes" id="notes"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Create Invoice</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Update price on product selection
            $(document).on('change', 'select[name^="items"][name$="[product_id]"]', function() {
                const selectedOption = $(this).find('option:selected');
                const priceInput = $(this).closest('.item').find('input[name$="[price]"]');
                priceInput.val(selectedOption.data('price'));
            });

            // Add new item
            $('#add-item').click(function() {
                const itemCount = $('.item').length;
                const itemDiv = $(`
                    <div class="item mb-3">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="product_id">Product:</label>
                                <select class="form-control" name="items[${itemCount}][product_id]" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="quantity">Quantity:</label>
                                <input type="number" class="form-control" name="items[${itemCount}][quantity]" value="1" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="price">Price:</label>
                                <input type="number" class="form-control" name="items[${itemCount}][price]" required readonly>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger remove-item">Remove</button>
                    </div>
                `);
                $('#items').append(itemDiv);

                // Update price when the new product is selected
                itemDiv.find('select[name^="items"][name$="[product_id]"]').change();

                itemDiv.find('.remove-item').click(function() {
                    itemDiv.remove();
                });
            });

            // Remove item
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item').remove();
            });
        });
    </script>
</body>

</html>
