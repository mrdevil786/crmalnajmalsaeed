@extends('admin.layout.main')

@section('admin-page-title', 'Expenditure')

@section('admin-main-section')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">Manage Expenditures</h1>
            <button class="btn btn-primary off-canvas" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa fa-plus-circle"></i> Add
                Expenditure</button>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Expenditures</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottomm" id="file-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">#</th>
                                    <th class="wd-20p border-bottom-0">Description</th>
                                    <th class="wd-15p border-bottom-0">Amount</th>
                                    <th class="wd-15p border-bottom-0">Category</th>
                                    <th class="wd-15p border-bottom-0">Payment Mode</th>
                                    <th class="wd-25p border-bottom-0">Invoice No</th>
                                    <th class="wd-15p border-bottom-0">Date</th>
                                    {{-- <th class="wd-25p border-bottom-0">Created At</th> --}}
                                    <th class="wd-25p border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenditures as $expenditure)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($expenditure->description, 20, '...') }}</td>
                                        <td>{{ $expenditure->amount }}</td>
                                        <td>{{ $expenditure->category }}</td>
                                        <td>{{ $expenditure->payment_method }}</td>
                                        <td>{{ $expenditure->invoice_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($expenditure->date)->format('Y-m-d H:i') }}</td>
                                        {{-- <td>{{ $expenditure->created_at }}</td> --}}
                                        <td class="text-center">
                                            <x-buttons.action-pill-button iconClass="fa fa-eye" iconColor="secondary"
                                                href="{{ route('admin.expenditures.view', $expenditure->id) }}" />

                                            @if (auth()->user()->user_role != 3)
                                                <x-buttons.action-pill-button
                                                    href="{{ route('admin.expenditures.edit', $expenditure->id) }}"
                                                    iconClass="fa fa-pencil" iconColor="warning"
                                                    modalTarget="editExpenditureModal" />
                                            @endif
                                            @if (auth()->user()->user_role == 1)
                                                <form action="{{ route('admin.expenditures.destroy', $expenditure->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-pill btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this expenditure?');">
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
    <x-Modal.Right-Offcanvas title="Add New Expenditure" action="{{ route('admin.expenditures.store') }}" method="POST">

        <x-fields.input-field label="Description" name="description" />
        <x-fields.input-field label="Amount" name="amount" />

        <x-fields.dropdown-field label="Category" name="category" :options="[
            'Rent' => 'Rent',
            'Salaries' => 'Salaries',
            'Utilities' => 'Utilities',
            'Office Supplies' => 'Office Supplies',
        ]" :selected="old('category')" id="category" />

        <x-fields.dropdown-field label="Payment Method" name="payment_method" :options="[
            'Cash' => 'Cash',
            'Bank Transfer' => 'Bank Transfer',
            'Cheque' => 'Cheque',
            'Credit Card' => 'Credit Card',
        ]" :selected="old('payment_method')"
            id="payment_method" />

        <x-fields.input-field label="Invoice Number" name="invoice_number" />
        <x-fields.input-field label="Date" name="date" type="datetime-local" />

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
@endsection
