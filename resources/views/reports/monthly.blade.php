@extends('layouts.app')

@section('title', 'Monthly Reports')

@section('content')
<div class="container">
    <h2>Monthly Reports</h2>
    <p>Approved orders placed in {{ now()->format('F Y') }}</p>

  

    {{-- Buttons for Print and PDF Download --}}
   <div class="mb-4 no-print">
    <!-- Download PDF -->
  <a href="{{ route('monthlyreport.downloadPdf', request()->only('search', 'perPage')) }}" class="btn btn-success btn-sm">
    Download PDF
</a>


    <!-- Print button -->
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        Print Report
    </button>
</div>

    {{-- Orders Table --}}
    <form action="{{ route('monthlyreport.index') }}" method="GET" class="mb-4 no-print">
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
        {{-- Wrap the table inside a scrollable div --}}
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-bordered table-sm">
                <thead>
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
                        <th>Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($approvedOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->product->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->owner->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->kebele->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->woreda->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->zone->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->shop->zone->region->name ?? 'N/A' }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ number_format($order->total_price, 2) }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->updated_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $order->product->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $order->updated_at->diffForHumans($order->product->created_at) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14">No approved orders found for this month.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Total Approved Monthly Revenue --}}
      
    </div>
    <div class="mt-4">
            <h4>Total Approved Monthly Revenue: {{ number_format($totalApprovedPrice, 2) }}</h4>
        </div>
</div>
@endsection

<style>
    /* Hide elements not required for printing */
    @media print {
        .no-print {
            display: none !important;
        }

        /* Ensure the print-area is visible and styled appropriately */
        .print-area {
            display: block;
        }
    }

    /* General styles for better print appearance */
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

    /* Adjusting page content on screen */
    .container {
        max-width: 100%;
        padding: 15px;
    }
</style>
