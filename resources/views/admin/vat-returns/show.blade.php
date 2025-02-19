@extends('admin.layout.main')

@section('admin-page-title', 'VAT Return Details')

@section('admin-main-section')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">VAT Return Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.vat-returns.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">VAT Period</span>
                                    <span class="info-box-number">
                                        {{ $vatReturn->period_from->format('d/m/Y') }} - {{ $vatReturn->period_to->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-{{ $vatReturn->status === 'submitted' ? 'success' : 'warning' }}">
                                    <i class="fas fa-file-alt"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Status</span>
                                    <span class="info-box-number">{{ ucfirst($vatReturn->status) }}</span>
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
                                    <td>{{ number_format($vatReturn->total_sales, 2) }} SAR</td>
                                </tr>
                                <tr>
                                    <th>Output VAT (15%)</th>
                                    <td>{{ number_format($vatReturn->output_vat, 2) }} SAR</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Purchases</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Purchases</th>
                                    <td>{{ number_format($vatReturn->total_purchases, 2) }} SAR</td>
                                </tr>
                                <tr>
                                    <th>Input VAT (15%)</th>
                                    <td>{{ number_format($vatReturn->input_vat, 2) }} SAR</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info"></i> Net VAT Payable</h5>
                                <h3>{{ number_format($vatReturn->net_vat_payable, 2) }} SAR</h3>
                            </div>
                        </div>
                    </div>

                    @if($vatReturn->notes)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Notes</h5>
                                </div>
                                <div class="card-body">
                                    {{ $vatReturn->notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
