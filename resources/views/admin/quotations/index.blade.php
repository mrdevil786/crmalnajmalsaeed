@extends('admin.layout.main')

@section('admin-page-title', 'Quotations')

@section('admin-main-section')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">Manage Quotations</h1>
            @if (Auth()->User()->user_role != 3)
                <a href="{{ route('admin.quotations.create') }}">
                    <button class="btn btn-primary off-canvas" type="button">Add Quotation</button>
                </a>
            @endif
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Quotations</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="file-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">#</th>
                                    <th class="wd-20p border-bottom-0">Quotation Number</th>
                                    <th class="wd-15p border-bottom-0">Customer</th>
                                    <th class="wd-15p border-bottom-0">Issue Date</th>
                                    <th class="wd-15p border-bottom-0">Due Date</th>
                                    <th class="wd-15p border-bottom-0">Total Amount</th>
                                    <th class="wd-25p border-bottom-0">Created At</th>
                                    <th class="wd-25p border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotations as $quotation)
                                    <!-- Change invoices to quotations -->
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $quotation->invoice_number }}</td>
                                        <td>{{ $quotation->customer->name }}</td>
                                        <td>{{ $quotation->issue_date }}</td>
                                        <td>{{ $quotation->due_date }}</td>
                                        <td>{{ $quotation->total }}</td>
                                        <td>{{ $quotation->created_at }}</td>
                                        <td class="text-center">
                                            <x-buttons.action-pill-button iconClass="fa fa-download" iconColor="success"
                                                href="{{ route('admin.quotations.download', $quotation->id) }}" />

                                            <x-buttons.action-pill-button iconClass="fa fa-eye" iconColor="secondary"
                                                href="{{ route('admin.quotations.stream', $quotation->id) }}" />

                                            @if (auth()->user()->user_role != 3)
                                                <x-buttons.action-pill-button
                                                    href="{{ route('admin.quotations.edit', $quotation->id) }}"
                                                    iconClass="fa fa-pencil" iconColor="warning"
                                                    modalTarget="editQuotationModal" />
                                            @endif
                                            @if (auth()->user()->user_role == 1)
                                                <form action="{{ route('admin.quotations.destroy', $quotation->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-pill btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this quotation?');">
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
@endsection
