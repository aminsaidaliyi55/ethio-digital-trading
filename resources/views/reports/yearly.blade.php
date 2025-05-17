@extends('layouts.app')

@section('title', 'Yearly Reports')

@section('content')
<div class="container">
    <h2>Yearly Reports</h2>
    <p>Approved orders placed in {{ now()->year }}</p> {{-- Show year instead of "F Y" for yearly report --}}

 <div class="mb-4 no-print">
    <!-- Download PDF -->
  <a href="{{ route('yearlyreport.downloadPdf', request()->only('search', 'perPage')) }}" class="btn btn-success btn-sm">
    Download PDF
</a>

    <!-- Print button -->
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        Print Report
    </button>
</div>

    {{-- Search and Pagination Controls --}}
    <form action="{{ route('yearlyreport.index') }}" method="GET" class="mb-4 no-print">
        <div class="row">
            <div class="col-md-4">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control form-control-sm" 
                    placeholder="Search by any field" 
                    value="{{ request('search') }}"
                >
            </div>
            <div class="col-md-2">
                <select name="perPage" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="5" {{ request('perPage') == '5' ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm">Search</button>
            </div>
        </div>
    </form>

    <div class="print-area">
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-bordered table-sm table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Shop</th>
                        <th>Owner</th>
                        <th>Kebele</th>
                        <th>Woreda</th>
                        <th>Zone</th>
                        <th>Region</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Sold At</th>
                        <th>Purchased At</th>
                        <th>Time Between Sold & Purchased</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($approvedOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration + ($approvedOrders->currentPage() - 1) * $approvedOrders->perPage() }}</td>
                            <td>{{ $order->product->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->owner->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->kebele->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->kebele->woreda->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->kebele->woreda->zone->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->kebele->woreda->zone->region->name ?? 'N/A' }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ number_format($order->total_price, 2) }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->updated_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $order->product->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $order->updated_at->diffForHumans($order->product->created_at, ['parts' => 3]) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14">No approved orders found for this year.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination links --}}
        <div class="mt-2">
            {{ $approvedOrders->withQueryString()->links() }}
        </div>

        {{-- Total Approved yearly Revenue --}}
        <div class="mt-4">
            <h4>Total Approved Yearly Revenue: {{ number_format($totalApprovedPrice, 2) }}</h4>
        </div>
    </div>
</div>
@endsection

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .print-area {
            display: block;
        }
    }

    @media print {
        body {
            font-size: 10px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
    }

    .container {
        max-width: 100%;
        padding: 15px;
    }

    table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    table tbody tr:nth-child(even) {
        background-color: #f1f1f1;
    }

    .thead-dark th {
        background-color: #343a40;
        color: white;
    }
</style>
