@extends('admin.layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Manage Suppliers</h3>
                    @can('manager')
                        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                            <i class="fe fe-plus"></i> Add Supplier
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">Name</th>
                                    <th class="wd-15p border-bottom-0">Email</th>
                                    <th class="wd-20p border-bottom-0">Phone</th>
                                    <th class="wd-15p border-bottom-0">Tax Number</th>
                                    <th class="wd-10p border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->tax_number }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.suppliers.view', $supplier->id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                                @can('manager')
                                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fe fe-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('admin')
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm" 
                                                            onclick="confirmDelete('{{ $supplier->id }}')">
                                                        <i class="fe fe-trash"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $supplier->id }}" 
                                                          action="{{ route('admin.suppliers.destroy', $supplier->id) }}" 
                                                          method="POST" 
                                                          class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endcan
                                            </div>
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
</div>
@endsection

@section('scripts')
    <script>
        function confirmDelete(supplierId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + supplierId).submit();
                }
            });
        }

        $(document).ready(function() {
            $('#responsive-datatable').DataTable();
        });
    </script>
@endsection 