@extends('admin.layout.main')

@section('admin-page-title', 'Purchases')

@section('admin-main-section')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">Manage Purchases</h1>
            @if (Auth()->User()->user_role != 3)
                <a href="{{ route('admin.purchases.create') }}">
                    <button class="btn btn-primary off-canvas" type="button">
                        <i class="fa fa-plus-circle"></i> Add Purchase
                    </button>
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
                    <h3 class="card-title">All Purchases</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="file-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">Purchase Number</th>
                                    <th class="wd-15p border-bottom-0">Supplier</th>
                                    <th class="wd-15p border-bottom-0">Date</th>
                                    <th class="wd-15p border-bottom-0">Due Date</th>
                                    <th class="wd-15p border-bottom-0">Total</th>
                                    <th class="wd-15p border-bottom-0">Status</th>
                                    <th class="wd-25p border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $purchase->purchase_number }}</td>
                                        <td>{{ $purchase->supplier->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') }}</td>
                                        <td>{{ $purchase->due_date ? \Carbon\Carbon::parse($purchase->due_date)->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ number_format($purchase->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $purchase->status === 'completed' ? 'success' : ($purchase->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <x-buttons.action-pill-button iconClass="fa fa-eye" iconColor="secondary"
                                                href="{{ route('admin.purchases.view', $purchase->id) }}" />

                                            @if (auth()->user()->user_role != 3)
                                                @if($purchase->status === 'pending')
                                                    <x-buttons.action-pill-button
                                                        href="{{ route('admin.purchases.edit', $purchase->id) }}"
                                                        iconClass="fa fa-pencil" iconColor="warning" />
                                                @endif
                                                
                                                <button type="button" 
                                                        class="btn btn-outline-{{ $purchase->status === 'pending' ? 'success' : 'warning' }} btn-pill btn-sm"
                                                        onclick="updateStatus('{{ $purchase->id }}', '{{ $purchase->status === 'pending' ? 'completed' : 'pending' }}')"
                                                        {{ $purchase->status === 'cancelled' ? 'disabled' : '' }}>
                                                    <i class="fa fa-{{ $purchase->status === 'pending' ? 'check' : 'clock' }}"></i>
                                                </button>
                                            @endif

                                            @if (auth()->user()->user_role == 1)
                                                @if($purchase->status === 'pending')
                                                    <form action="{{ route('admin.purchases.destroy', $purchase->id) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-pill btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this purchase?');">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
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

    <script>
        function updateStatus(purchaseId, newStatus) {
            if (confirm(`Are you sure you want to mark this purchase as ${newStatus}?`)) {
                $.ajax({
                    url: "{{ route('admin.purchases.status') }}",
                    type: 'PUT',
                    data: {
                        id: purchaseId,
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Something went wrong.');
                    }
                });
            }
        }
    </script>
@endsection