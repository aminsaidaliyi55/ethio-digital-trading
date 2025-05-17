@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2>@lang('messages.Orders')</h2>

    <div class="d-flex justify-content-between mb-3">
        <div class="badge bg-info text-white px-3 py-2">
            <strong>@lang('messages.Total Orders'):</strong> {{ $orders->total() }}
        </div>
        <div class="badge bg-warning text-dark px-3 py-2">
            <strong>@lang('messages.Pending'):</strong> {{ $orders->where('status', 'pending')->count() }}
        </div>
        <div class="badge bg-success text-white px-3 py-2">
            <strong>@lang('messages.Completed'):</strong> {{ $orders->where('status', 'completed')->count() }}
        </div>
        <div class="badge bg-danger text-white px-3 py-2">
            <strong>@lang('messages.Cancelled'):</strong> {{ $orders->where('status', 'cancelled')->count() }}
        </div>
    </div>

    <form action="{{ route('orders.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="@lang('messages.Search orders')" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="perPage" class="form-select" onchange="this.form.submit()">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">@lang('messages.Search')</button>
            </div>
        </div>
    </form>

    <!-- Orders Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('messages.Customer')</th>
                <th>@lang('messages.Product')</th>
                <th>@lang('messages.Quantity')</th>
                <th>@lang('messages.Total Price')</th>
                <th>@lang('messages.Shop Owner')</th>
                <th>@lang('messages.Shop TIN')</th>
                <th>@lang('messages.Kebele')</th>
                <th>@lang('messages.Woreda')</th>
                <th>@lang('messages.Zone')</th>
                <th>@lang('messages.Region')</th>
                <th>@lang('messages.Status')</th>
                <th>@lang('messages.Approved By')</th>
                <th>@lang('messages.Actions')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    @if(Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'FederalAdmin', 'RegionalAdmin', 'ZoneAdmin', 'WoredaAdmin', 'KebeleAdmin', 'Owners', 'Customer']))
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->user->name ?? __('messages.N/A') }}</td>
                        <td>{{ $order->product->name ?? __('messages.N/A') }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->product->shop->owner->name ?? __('messages.N/A') }}</td>
                        <td>{{ $order->product->shop->TIN ?? __('messages.N/A') }}</td>
                        <td>{{ $order->product->shop->kebele->name ?? __('messages.N/A') }}</td>
                        <td>{{ $order->product->shop->kebele->woreda->name ?? __('messages.N/A') }}</td>
                        <td>{{ $order->product->shop->zone->name ?? __('messages.N/A') }}</td>
                        <td>{{ $order->product->shop->region->name ?? __('messages.N/A') }}</td>
                        <td>
                            <span class="badge 
                                @if($order->status == 'pending') bg-warning 
                                @elseif($order->status == 'completed') bg-success 
                                @elseif($order->status == 'cancelled') bg-danger 
                                @elseif($order->status == 'approved') bg-primary 
                                @endif">
                                @lang('messages.' . ucfirst($order->status))
                            </span>
                        </td>
                    @endif
                    <td>
                        @if($order->status == 'completed')
                            {{ $order->approvedBy->name ?? __('messages.N/A') }}<br>
                            <a href="{{ route('orders.receipt', $order->id) }}" class="btn btn-success btn-sm mt-1">
                                @lang('messages.Download Receipt')
                            </a>
                        @endif
                    </td>
                    <td>
                        @if($order->status !== 'completed' && $order->status !== 'cancelled' && !Auth::user()->hasRole('Customer'))
                            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('POST')
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>@lang('messages.Pending')</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>@lang('messages.Completed')</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>@lang('messages.Cancelled')</option>
                                </select>
                            </form>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-1" onclick="return confirm('@lang('messages.Are you sure you want to delete this order?')')">
                                    @lang('messages.Delete')
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center">@lang('messages.No orders found.')</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection
