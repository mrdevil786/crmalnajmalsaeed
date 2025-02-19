@extends('admin.layout.main')

@section('admin-page-title', isset($data['status']) ? 'VAT Return Details' : 'VAT Return Preview')

@section('admin-main-section')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">{{ isset($data['status']) ? 'VAT Return Details' : 'VAT Return Preview' }}</h1>
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
                    <h3 class="card-title">VAT Return Details</h3>
                    @if(isset($data['status']))
                        <span class="badge bg-{{ $data['status'] === 'submitted' ? 'success' : 'warning' }} ms-2">
                            {{ ucfirst($data['status']) }}
                        </span>
                    @endif
                </div>
                @if(!isset($data['status']))
                <form action="{{ route('admin.vat-returns.store') }}" method="POST">
                    @csrf
                @endif
                    <div class="card-body">
                        <!-- VAT Period Card -->
                        <div class="col-md-6 mx-auto">
                            <div class="card custom-card bg-dark-transparent">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-lg bg-dark-transparent rounded-3">
                                                <i class="fa fa-calendar text-dark"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">VAT Period</h6>
                                            <div class="fs-4 fw-semibold">
                                                {{ $data['period_from']->format('d/m/Y') }} - {{ $data['period_to']->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <!-- Sales Card -->
                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-header bg-info-transparent">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">
                                                <i class="fa fa-shopping-cart text-info"></i>
                                            </span>
                                            <h6 class="card-title mb-0">Sales</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table border text-nowrap text-md-nowrap table-bordered mb-0">
                                                <tr>
                                                    <th class="bg-light">Total Sales</th>
                                                    <td class="fw-bold">{{ number_format($data['total_sales'], 2) }} SAR</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">Output VAT (15%)</th>
                                                    <td class="fw-bold text-info">{{ number_format($data['output_vat'], 2) }} SAR</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Purchases Card -->
                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-header bg-info-transparent">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">
                                                <i class="fa fa-shopping-bag text-info"></i>
                                            </span>
                                            <h6 class="card-title mb-0">Purchases</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table border text-nowrap text-md-nowrap table-bordered mb-0">
                                                <tr>
                                                    <th class="bg-light">Total Purchases</th>
                                                    <td class="fw-bold">{{ number_format($data['total_purchases'], 2) }} SAR</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">Input VAT (15%)</th>
                                                    <td class="fw-bold text-info">{{ number_format($data['input_vat'], 2) }} SAR</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Net VAT Payable Card -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card custom-card bg-{{ $data['net_vat_payable'] < 0 ? 'danger' : 'success' }}-transparent">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <span class="avatar avatar-lg bg-{{ $data['net_vat_payable'] < 0 ? 'danger' : 'success' }}-transparent rounded-3">
                                                    <i class="fa fa-money text-{{ $data['net_vat_payable'] < 0 ? 'danger' : 'success' }}"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Net VAT Payable</h6>
                                                <div class="fs-3 fw-bold text-{{ $data['net_vat_payable'] < 0 ? 'danger' : 'success' }}">
                                                    {{ number_format($data['net_vat_payable'], 2) }} SAR
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group mt-4">
                            <label class="form-label" for="notes">Notes</label>
                            @if(isset($data['status']))
                                <div class="form-control bg-light">{{ $data['notes'] ?? 'No notes available' }}</div>
                            @else
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter any additional notes here...">{{ old('notes') }}</textarea>
                            @endif
                        </div>

                        @if(!isset($data['status']))
                            <!-- Hidden fields -->
                            @foreach ($data as $key => $value)
                                @if($key !== 'notes')
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                        @endif
                    </div>
                    @if(!isset($data['status']))
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Submit VAT Return
                            </button>
                        </div>
                    @endif
                @if(!isset($data['status']))
                </form>
                @endif
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection
