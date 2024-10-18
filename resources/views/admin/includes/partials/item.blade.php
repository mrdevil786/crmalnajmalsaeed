<div class="item mb-3">
    <div class="row align-items-end">
        <div class="col-xl-4 col-md-6 mb-3">
            <label class="form-label" for="product_id">Product</label>
            <select class="form-select" name="items[{{ $index }}][product_id]" required>
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                        {{ isset($item) && $item->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <label class="form-label" for="quantity">Quantity</label>
            <input type="number" class="form-control" name="items[{{ $index }}][quantity]"
                value="{{ isset($item) ? $item->quantity : 1 }}" step="0.01" required>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <label class="form-label" for="total_price">Total Price</label>
            <input type="text" class="form-control total_price"
                value="{{ isset($item) ? $item->quantity * $item->price : '' }}" readonly>
        </div>

        <div class="col-xl-1 col-md-6 d-flex justify-content-center align-items-center mb-3">
            <button type="button" class="btn btn-danger remove-item" title="Remove Item">
                <i class="fe fe-trash"></i>
            </button>
        </div>
    </div>

    <input type="hidden" class="form-control" name="items[{{ $index }}][price]"
        value="{{ isset($item) ? $item->price : '' }}" required>

    @isset($item)
        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
    @endisset
</div>
