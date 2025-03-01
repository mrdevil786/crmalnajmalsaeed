@extends('admin.layout.main')

@section('admin-page-title', 'Customer')

@section('admin-main-section')

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">Manage Customers</h1>
            <button class="btn btn-primary off-canvas" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa fa-plus-circle"></i> Add
                Customer</button>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Customers</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottomm" id="file-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">#</th>
                                    <th class="wd-20p border-bottom-0">Name</th>
                                    <th class="wd-15p border-bottom-0">Email</th>
                                    <th class="wd-15p border-bottom-0">Address</th>
                                    <th class="wd-15p border-bottom-0">Pincode</th>
                                    <th class="wd-15p border-bottom-0">Tax Number</th>
                                    <th class="wd-25p border-bottom-0">Updated At</th>
                                    <th class="wd-25p border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ \Illuminate\Support\Str::limit($customer->name, 20, '...') }}</td>
                                        <td class="align-middle">{{ \Illuminate\Support\Str::limit($customer->email, 20, '...') }}</td>
                                        <td class="align-middle">
                                            @if(empty($customer->address))
                                                <div class="text-center">
                                                    <x-extras.small-pill pill-color="dark" pill-text="Empty"/>
                                                </div>
                                            @else
                                                {{ \Illuminate\Support\Str::limit($customer->address, 20, '...') }}
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if(empty($customer->pincode))
                                                <div class="text-center">
                                                    <x-extras.small-pill pill-color="dark" pill-text="Empty"/>
                                                </div>
                                            @else
                                                {{ $customer->pincode }}
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if(empty($customer->tax_number))
                                                <div class="text-center">
                                                    <x-extras.small-pill pill-color="dark" pill-text="Empty"/>
                                                </div>
                                            @else
                                                {{ $customer->tax_number }}
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $customer->updated_at }}</td>
                                        <td class="align-middle text-center">
                                            <x-buttons.action-pill-button iconClass="fa fa-eye" iconColor="secondary"
                                                href="{{ route('admin.customers.view', $customer->id) }}" />

                                            @if (auth()->user()->user_role != 3)
                                                <x-buttons.action-pill-button
                                                    href="{{ route('admin.customers.edit', $customer->id) }}"
                                                    iconClass="fa fa-pencil" iconColor="warning"
                                                    modalTarget="editUserModal" />
                                            @endif
                                            @if (auth()->user()->user_role == 1)
                                                <x-buttons.delete-button
                                                    :route="route('admin.customers.destroy', $customer->id)"
                                                    confirm-message="Are you sure you want to delete this customer?" />
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
    <x-Modal.Right-Offcanvas title="Add New Customer" action="{{ route('admin.customers.store') }}" method="POST">

        <x-fields.input-field label="Full Name" name="name" />
        <x-fields.input-field label="Email" name="email" />
        <x-fields.input-field label="Address" name="address" />
        <x-fields.input-field label="Pincode" name="pincode" />
        <x-fields.input-field label="Tax Number" name="tax_number" />

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
