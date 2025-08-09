@extends('admin.layout.main')

@section('admin-page-title', $isCreate ? 'Create Product' : ($isEdit ? 'Edit Product' : 'View Product'))

@section('admin-main-section')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ $isCreate ? 'Create Product' : ($isEdit ? 'Edit Product' : 'View Product') }}</h1>
            <a href="{{ route('admin.products.index') }}" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i>
                Back</a>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $isCreate ? 'Create Product' : ($isEdit ? 'Edit Product' : 'View Product') }}</h3>
                </div>
                <div class="card-body">
                    @if ($isCreate)
                        <form method="POST" action="{{ route('admin.products.store') }}">
                            @csrf
                    @elseif ($isEdit)
                        <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
                            @method('PUT')
                            @csrf
                    @endif

                    <div class="form-row">
                        <div class="col-xl-6 mb-3">
                            <label class="form-label mt-0" for="name">Product Name</label>
                            @if ($isCreate || $isEdit)
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name', $isCreate ? '' : $product->name) }}">
                            @else
                                <p class="form-control">{{ $product->name }}</p>
                            @endif
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-6 mb-3">
                            <label class="form-label mt-0" for="type">Product Type</label>
                            @if ($isCreate || $isEdit)
                                <select class="form-control" id="type" name="type">
                                    <option value="goods" {{ old('type', ($isCreate ? '' : $product->type)) === 'goods' ? 'selected' : '' }}>Goods</option>
                                    <option value="services" {{ old('type', ($isCreate ? '' : $product->type)) === 'services' ? 'selected' : '' }}>Services</option>
                                </select>
                            @else
                                <p class="form-control">{{ ucfirst($product->type) }}</p>
                            @endif
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-6 mb-3">
                            <label class="form-label mt-0" for="price">Price</label>
                            @if ($isCreate || $isEdit)
                                <input type="number" class="form-control" id="price" name="price"
                                       value="{{ old('price', $isCreate ? '' : $product->price) }}" step="0.01">
                            @else
                                <p class="form-control">{{ $product->price }}</p>
                            @endif
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-6 mb-3">
                            <label class="form-label mt-0" for="unit">Unit</label>
                            @if ($isCreate || $isEdit)
                                <input type="text" class="form-control" id="unit" name="unit"
                                       value="{{ old('unit', $isCreate ? '' : $product->unit) }}">
                            @else
                                <p class="form-control">{{ $product->unit }}</p>
                            @endif
                            @error('unit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-12 mb-3">
                            <label class="form-label mt-0" for="description">Description</label>
                            @if ($isCreate || $isEdit)
                                <textarea class="form-control" id="description" name="description">{{ old('description', $isCreate ? '' : $product->description) }}</textarea>
                            @else
                                <p class="form-control">{{ $product->description }}</p>
                            @endif
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    @if ($isCreate || $isEdit)
                        <center>
                            <button class="btn btn-primary" type="submit">
                                {{ $isCreate ? 'Create Product' : 'Update Product' }}
                            </button>
                        </center>
                    @endif

                    @if ($isCreate || $isEdit)
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

@endsection

@section('custom-script')
    <!-- INPUT MASK JS-->
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

    <!-- FORM VALIDATION JS -->
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection


