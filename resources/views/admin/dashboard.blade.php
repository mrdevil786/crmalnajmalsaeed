@extends('admin.layout.main')

@section('admin-page-title', 'Dashboard')

@section('admin-main-section')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW-1 -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <a href="{{ route('admin.customers.index') }}" style="color: inherit; text-decoration: none;">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Total Clients</h6>
                                    <h2 class="mb-0 number-font">{{ $totalCustomers }}</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                            <span class="text-muted fs-12">
                                <span class="{{ $customerPercentageChange > 0 ? 'text-secondary' : 'text-danger' }}">
                                    <i
                                        class="{{ $customerPercentageChange > 0 ? 'fe fe-arrow-up-circle' : 'fe fe-arrow-down-circle' }}"></i>
                                    {{ $customerPercentageChange > 0 ? '+' : '' }}{{ round($customerPercentageChange, 2) }}%
                                </span>
                                From {{ $previousThreeMonthDateRange }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <a href="{{ route('admin.products.index') }}" style="color: inherit; text-decoration: none;">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Total Products</h6>
                                    <h2 class="mb-0 number-font">{{ $totalProducts }}</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="leadschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                            <span class="text-muted fs-12">
                                <span class="{{ $productPercentageChange > 0 ? 'text-pink' : 'text-danger' }}">
                                    <i
                                        class="{{ $productPercentageChange > 0 ? 'fe fe-arrow-up-circle' : 'fe fe-arrow-down-circle' }}"></i>
                                    {{ $productPercentageChange > 0 ? '+' : '' }}{{ round($productPercentageChange, 2) }}%
                                </span>
                                From {{ $previousThreeMonthDateRange }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2">
                                <h6 class="">Total VAT</h6>
                                <h2 class="mb-0 number-font">{{ number_format($totalVatAmount, 2) }}</h2>
                            </div>
                            <div class="ms-auto">
                                <div class="chart-wrapper mt-1">
                                    <canvas id="profitchart" class="h-8 w-9 chart-dropshadow"></canvas>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted fs-12">
                            <span class="{{ $vatPercentageChange > 0 ? 'text-green' : 'text-danger' }}">
                                <i
                                    class="{{ $vatPercentageChange > 0 ? 'fe fe-arrow-up-circle' : 'fe fe-arrow-down-circle' }}"></i>
                                {{ $vatPercentageChange > 0 ? '+' : '' }}{{ round($vatPercentageChange, 2) }}%
                            </span>
                            From {{ $previousThreeMonthDateRange }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2">
                                <h6 class="">Total Income</h6>
                                <h2 class="mb-0 number-font">{{ number_format($totalIncome, 2) }}</h2>
                            </div>
                            <div class="ms-auto">
                                <div class="chart-wrapper mt-1">
                                    <canvas id="costchart" class="h-8 w-9 chart-dropshadow"></canvas>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted fs-12">
                            <span class="{{ $incomePercentageChange > 0 ? 'text-warning' : 'text-danger' }}">
                                <i
                                    class="{{ $incomePercentageChange > 0 ? 'fe fe-arrow-up-circle' : 'fe fe-arrow-down-circle' }}"></i>
                                {{ $incomePercentageChange > 0 ? '+' : '' }}{{ round($incomePercentageChange, 2) }}%
                            </span>
                            From {{ $previousThreeMonthDateRange }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ROW-1 END -->

<!-- ROW-2 -->
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sales Analytics</h3>
            </div>
            <div class="card-body">
                <div class="d-flex mx-auto text-center justify-content-center mb-4">
                    <div class="d-flex text-center justify-content-center me-3">
                        <span class="dot-label bg-primary my-auto"></span>Total Sales
                    </div>
                    <div class="d-flex text-center justify-content-center">
                        <span class="dot-label bg-secondary my-auto"></span>Total Orders
                    </div>
                </div>
                <div class="chartjs-wrapper-demo">
                    <canvas id="transactions" class="chart-dropshadow"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body pb-0 bg-recentorder">
                <h3 class="card-title text-white">Recent Orders</h3>
                <div class="chartjs-wrapper-demo">
                    <canvas id="recentorders" class="chart-dropshadow"></canvas>
                </div>
            </div>
            <div id="flotback-chart" class="flot-background"></div>
            <div class="card-body">
                <div class="d-flex mb-4 mt-3">
                    <div class="avatar avatar-md bg-secondary-transparent text-secondary bradius me-3">
                        <i class="fe fe-check"></i>
                    </div>
                    <div class="">
                        <h6 class="mb-1 fw-semibold">Delivered Orders</h6>
                        <p class="fw-normal fs-12"><span class="text-success">3.5%</span> increased</p>
                    </div>
                    <div class="ms-auto my-auto">
                        <p class="fw-bold fs-20">1,768</p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="avatar avatar-md bg-pink-transparent text-pink bradius me-3">
                        <i class="fe fe-x"></i>
                    </div>
                    <div class="">
                        <h6 class="mb-1 fw-semibold">Cancelled Orders</h6>
                        <p class="fw-normal fs-12"><span class="text-success">1.2%</span> increased</p>
                    </div>
                    <div class="ms-auto my-auto">
                        <p class="fw-bold fs-20 mb-0">3,675</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->
</div>
<!-- ROW-2 END -->
@endsection