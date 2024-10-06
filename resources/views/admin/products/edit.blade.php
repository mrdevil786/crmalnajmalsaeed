@extends('admin.layout.main')

@section('admin-page-title', $isEdit ? 'Edit Product' : 'View Product')

@section('admin-main-section')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ $isEdit ? 'Edit Product' : 'View Product' }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $isEdit ? 'Edit Product' : 'View Product' }}</h3>
                </div>
                <div class="card-body">
                    @if ($isEdit)
                        <form method="POST" action="{{ route('admin.products.update', $product->id) }}"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                    @endif

                    <div class="form-row">
                        <div class="col-xl-4 mb-3">
                            <label class="form-label mt-0" for="name">Product Name</label>
                            @if ($isEdit)
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $product->name) }}">
                            @else
                                <p class="form-control">{{ $product->name }}</p>
                            @endif
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-4 mb-3">
                            <label class="form-label mt-0" for="price">Price</label>
                            @if ($isEdit)
                                <input type="number" class="form-control" id="price" name="price"
                                    value="{{ old('price', $product->price) }}" step="0.01">
                            @else
                                <p class="form-control">{{ $product->price }}</p>
                            @endif
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-4 mb-3">
                            <label class="form-label mt-0" for="unit">Unit</label>
                            @if ($isEdit)
                                <input type="text" class="form-control" id="unit" name="unit"
                                    value="{{ old('unit', $product->unit) }}">
                            @else
                                <p class="form-control">{{ $product->unit }}</p>
                            @endif
                            @error('unit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-4 mb-3">
                            <label class="form-label mt-0" for="description">Description</label>
                            @if ($isEdit)
                                <textarea class="form-control" id="description" name="description">{{ old('description', $product->description) }}</textarea>
                            @else
                                <p class="form-control">{{ $product->description }}</p>
                            @endif
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    @if ($isEdit)
                        <center><button class="btn btn-primary" type="submit">Update Product</button></center>
                    @endif

                    @if ($isEdit)
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

@endsection

@section('custom-script')
    <!-- DATA TABLE JS-->
    <!-- INPUT MASK JS-->
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

    <!-- FORM VALIDATION JS -->
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection
