<table>
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
            <th>Sold (Duration)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($approvedOrders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
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
                <td>{{ $order->updated_at->diffForHumans($order->product->created_at) }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="9">Total Approved Revenue</td>
            <td colspan="5">{{ number_format($totalApprovedPrice, 2) }}</td>
        </tr>
    </tbody>
</table>
