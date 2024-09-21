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
        <form id="invoice-form">
            <div class="form-group">
                <label for="customer_id">Customer ID:</label>
                <input type="number" class="form-control" id="customer_id" required>
            </div>

            <div class="form-group">
                <label for="type">Invoice Type:</label>
                <select class="form-control" id="type" required>
                    <option value="invoice">Invoice</option>
                    <option value="quote">Quote</option>
                </select>
            </div>

            <div class="form-group">
                <label for="issue_date">Issue Date:</label>
                <input type="date" class="form-control" id="issue_date" required>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date:</label>
                <input type="date" class="form-control" id="due_date">
            </div>

            <div class="form-group">
                <label for="vat_percentage">VAT Percentage:</label>
                <input type="number" class="form-control" id="vat_percentage" value="15" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="discount">Discount:</label>
                <input type="number" class="form-control" id="discount" value="0" step="0.01">
            </div>

            <h2>Items</h2>
            <div id="items" class="mb-3">
                <div class="item mb-3">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="product_id">Product ID:</label>
                            <input type="number" class="form-control product_id" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="quantity">Quantity:</label>
                            <input type="number" class="form-control quantity" value="1" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="price">Price:</label>
                            <input type="number" class="form-control price" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
            <button type="button" id="add-item" class="btn btn-primary">Add Item</button>

            <div class="form-group mt-3">
                <label for="notes">Notes:</label>
                <textarea class="form-control" id="notes"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Create Invoice</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('add-item').addEventListener('click', function() {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'item mb-3';
            itemDiv.innerHTML = `
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="product_id">Product ID:</label>
                <input type="number" class="form-control product_id" required>
            </div>
            <div class="form-group col-md-4">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control quantity" value="1" required>
            </div>
            <div class="form-group col-md-4">
                <label for="price">Price:</label>
                <input type="number" class="form-control price" required>
            </div>
        </div>
        <button type="button" class="btn btn-danger remove-item">Remove</button>
    `;
            document.getElementById('items').appendChild(itemDiv);

            itemDiv.querySelector('.remove-item').addEventListener('click', function() {
                itemDiv.remove();
            });
        });

        document.getElementById('invoice-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const customerId = document.getElementById('customer_id').value;
            const type = document.getElementById('type').value;
            const issueDate = document.getElementById('issue_date').value;
            const dueDate = document.getElementById('due_date').value;
            const vatPercentage = document.getElementById('vat_percentage').value;
            const discount = document.getElementById('discount').value;
            const notes = document.getElementById('notes').value;

            const items = Array.from(document.querySelectorAll('.item')).map(item => ({
                product_id: item.querySelector('.product_id').value,
                quantity: item.querySelector('.quantity').value,
                price: item.querySelector('.price').value,
            }));

            const invoiceData = {
                customer_id: customerId,
                type: type,
                issue_date: issueDate,
                due_date: dueDate,
                vat_percentage: vatPercentage,
                discount: discount,
                notes: notes,
                items: items,
            };

            fetch('/api/invoices', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(invoiceData),
                })
                .then(response => response.json())
                .then(data => {
                    alert('Invoice created successfully!');
                    // Optionally, reset the form or redirect
                })
                .catch(error => {
                    alert('Error creating invoice: ' + error.message);
                });
        });
    </script>
</body>

</html>
