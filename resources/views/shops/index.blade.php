@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Shop Management</h1>
    <div class="container">
        <!-- Add New Shop Button -->
        @can('create-shop')
            <a href="{{ route('shops.create') }}" class="btn btn-primary mb-3">Add New Shop</a>
        @endcan
    </div>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <!-- Search Form -->
    <form action="{{ route('shops.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="phone" class="form-control" placeholder="Search by phone" value="{{ request('phone') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="category" class="form-control" placeholder="Search by category" value="{{ request('category') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <!-- Display Total Shops (Before or After Search) -->
    <div class="mb-3">
        <strong>Total Shops:</strong> {{ $shops->total() }}
    </div>

    <!-- Table Scrollable Container -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
        <thead>
    <tr>
        <th>Name</th>
        <th>Owner</th>
        <th>Phone</th>
        <th>Website</th>
        <th>Total Capital</th>
        <th>Total Product</th>
        <th>Total product price</th>
        <th>TIN</th>
         <th>Federal</th>
        <th>Region</th>
        <th>Zone</th>
        <th>Woreda</th>
        <th>Kebele</th>
        <th>Category</th>
                @can('create-shop')

        <th>Shop License</th>
        @endcan
        <th>Opening Hours</th>
        <th>Status</th>
        @can('edit-shop')
            <th>Change Status</th>
            <th>Edit</th>
        @endcan
        <th>View Shop</th>
    </tr>
</thead>
<tbody>
    @foreach($shops as $shop)
        <tr id="shop-row-{{ $shop->id }}">
            <td>{{ $shop->name }}</td>
            <td>{{ $shop->owner->name ?? 'N/A' }}</td>
            <td>{{ $shop->phone }}</td>
            <td> <a href="{{ $shop->website }}">{{ $shop->website }}</a></td>
            <td>{{ $shop->total_capital }}</td>
            <td>{{ $shop->products->sum('stock_quantity') }}</td>
<td>{{ number_format($shop->products->sum(function ($product) {
    return $product->stock_quantity * $product->selling_price;
}), 2) }}</td>

            <td>{{ $shop->TIN }}</td>
            <td>{{ $shop->woreda->zone->region->federal->name ?? 'N/A' }}</td>
            <td>{{ $shop->woreda->zone->region->name ?? 'N/A' }}</td>
            <td>{{ $shop->woreda->zone->name ?? 'N/A' }}</td>
            <td>{{ $shop->woreda->name ?? 'N/A' }}</td>
            <td>{{ $shop->kebele->name ?? 'N/A' }}</td>
            <td>{{ $shop->category->name ?? 'N/A' }}</td>
                            @can('create-shop')
<td>
                @if($shop->shop_license_path)
                    <a href="{{ asset('storage/' . $shop->shop_license_path) }}" class="btn btn-info btn-sm" target="_blank">View License</a>
                @else
                    <span class="text-danger">No License</span>
                @endif
            </td>
            @endcan
            <td>{{ $shop->opening_hours }}</td>
            <td id="status-{{ $shop->id }}">{{ $shop->status }}</td>

            @can('edit-shop')
                <td>
                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#changeStatusModal{{ $shop->id }}">Change Status</button>
                </td>
               
                <td>
                    <a href="{{ route('shops.edit', $shop->id) }}" class="btn btn-warning btn-sm">Edit</a>
                </td>
            @endcan
            <td>
                <a href="{{ route('shops.show', $shop->id) }}" class="btn btn-success btn-sm">View</a>
            </td>
        </tr>
    @endforeach
    @if($shops->isEmpty())
        <tr>
            <td colspan="19" class="text-center">No shops found.</td>
        </tr>
    @endif
</tbody>

        </table>
        <div class="d-flex justify-content-center">
            {{ $shops->links() }}
        </div>
    </div>
</div>

<!-- Modal for Changing Status (Move this out of the table) -->
@foreach($shops as $shop)
    <div class="modal fade" id="changeStatusModal{{ $shop->id }}" tabindex="-1" aria-labelledby="changeStatusModalLabel{{ $shop->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Status for {{ $shop->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="status-form-{{ $shop->id }}" action="{{ route('shops.changeStatus', $shop->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="status-select-{{ $shop->id }}">Status</label>
                            <select id="status-select-{{ $shop->id }}" name="status" class="form-control">
                                <option value="active" {{ $shop->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $shop->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
