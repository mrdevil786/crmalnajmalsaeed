@extends('admin.layout.main')

@section('admin-page-title', 'VAT Return Preview')

@section('admin-main-section')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">VAT Return Preview</h3>
                </div>
                <form action="{{ route('admin.vat-returns.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">VAT Period</span>
                                        <span class="info-box-number">
                                            {{ $data['period_from']->format('d/m/Y') }} - {{ $data['period_to']->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h5>Sales</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Total Sales</th>
                                        <td>{{ number_format($data['total_sales'], 2) }} SAR</td>
                                    </tr>
                                    <tr>
                                        <th>Output VAT (15%)</th>
                                        <td>{{ number_format($data['output_vat'], 2) }} SAR</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Purchases</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Total Purchases</th>
                                        <td>{{ number_format($data['total_purchases'], 2) }} SAR</td>
                                    </tr>
                                    <tr>
                                        <th>Input VAT (15%)</th>
                                        <td>{{ number_format($data['input_vat'], 2) }} SAR</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-info"></i> Net VAT Payable</h5>
                                    <h3>{{ number_format($data['net_vat_payable'], 2) }} SAR</h3>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Hidden fields -->
                        @foreach($data as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Submit VAT Return
                        </button>
                        <a href="{{ route('admin.vat-returns.create') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
