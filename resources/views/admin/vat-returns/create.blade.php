@extends('admin.layout.main')

@section('admin-page-title', 'Calculate VAT Return')

@section('admin-main-section')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">Calculate VAT Return</h1>
            <a href="{{ route('admin.vat-returns.index') }}" class="btn btn-danger">
                <i class="fa fa-arrow-circle-left"></i> Back
            </a>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Select Period</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vat-returns.calculate') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col-xl-6 mb-3">
                                <label class="form-label mt-0" for="period_from">Period From</label>
                                <input type="date" class="form-control @error('period_from') is-invalid @enderror" 
                                       id="period_from" name="period_from" value="{{ old('period_from') }}" required>
                                @error('period_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label class="form-label mt-0" for="period_to">Period To</label>
                                <input type="date" class="form-control @error('period_to') is-invalid @enderror" 
                                       id="period_to" name="period_to" value="{{ old('period_to') }}" required>
                                @error('period_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-footer mt-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-calculator"></i> Calculate VAT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection

@section('custom-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodFrom = document.getElementById('period_from');
            const periodTo = document.getElementById('period_to');

            periodFrom.addEventListener('change', function() {
                periodTo.min = this.value;
            });

            periodTo.addEventListener('change', function() {
                periodFrom.max = this.value;
            });
        });
    </script>
@endsection
