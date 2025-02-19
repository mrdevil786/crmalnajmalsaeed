@extends('admin.layout.main')

@section('admin-page-title', 'VAT Returns')

@section('admin-main-section')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">Manage VAT Returns</h1>
            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <i class="fa fa-plus-circle"></i> File VAT Return
            </button>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All VAT Returns</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="file-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">#</th>
                                    <th class="wd-20p border-bottom-0">Period</th>
                                    <th class="wd-15p border-bottom-0">Total Sales</th>
                                    <th class="wd-15p border-bottom-0">Total Purchases</th>
                                    <th class="wd-15p border-bottom-0">Net VAT Payable</th>
                                    <th class="wd-15p border-bottom-0">Status</th>
                                    <th class="wd-25p border-bottom-0">Updated At</th>
                                    <th class="wd-25p border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vatReturns as $vatReturn)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $vatReturn->period_from->format('d/m/Y') }} - {{ $vatReturn->period_to->format('d/m/Y') }}</td>
                                        <td>{{ number_format($vatReturn->total_sales, 2) }} SAR</td>
                                        <td>{{ number_format($vatReturn->total_purchases, 2) }} SAR</td>
                                        <td>{{ number_format($vatReturn->net_vat_payable, 2) }} SAR</td>
                                        <td>
                                            <span class="badge bg-{{ $vatReturn->status === 'submitted' ? 'success' : 'warning' }}">
                                                {{ ucfirst($vatReturn->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $vatReturn->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.vat-returns.show', $vatReturn->id) }}"
                                               class="btn btn-sm btn-outline-primary btn-pill">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if($vatReturn->status === 'draft')
                                                <form action="{{ route('admin.vat-returns.update-status', $vatReturn->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-success btn-pill"
                                                            onclick="return confirm('Are you sure you want to submit this VAT return? This action cannot be undone.')">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($vatReturn->status === 'draft')
                                                <form action="{{ route('admin.vat-returns.destroy', $vatReturn->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-pill"
                                                            onclick="return confirm('Are you sure you want to delete this VAT return? This action cannot be undone.')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

    <!--Add Modal - Right Offcanvas-->
    <x-Modal.Right-Offcanvas id="offcanvasRight" title="Calculate VAT Return" action="{{ route('admin.vat-returns.calculate') }}" method="POST">
        <div class="col-xl-12 mb-3">
            <label class="form-label mt-0" for="period_from">Period From</label>
            <input type="date" class="form-control @error('period_from') is-invalid @enderror"
                   id="period_from" name="period_from" value="{{ old('period_from') }}" required>
            @error('period_from')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xl-12 mb-3">
            <label class="form-label mt-0" for="period_to">Period To</label>
            <input type="date" class="form-control @error('period_to') is-invalid @enderror"
                   id="period_to" name="period_to" value="{{ old('period_to') }}" required>
            @error('period_to')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </x-Modal.Right-Offcanvas>
    <!--/Right Offcanvas-->
@endsection

@section('custom-script')
    <!-- DATA TABLE JS-->
    <script src="{{ asset('../assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('../assets/js/table-data.js') }}"></script>

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
