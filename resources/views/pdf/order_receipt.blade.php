<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 30px;
            color: #333;
            background: #f9f9f9;
        }

        .flag-container {
            text-align: center;
            margin-bottom: 2px;
        }

        .flag-container img.flag {
            height: 60px;
            width: auto;
        }

        .header {
            background-color: #078930;
            color: white;
            text-align: center;
            padding: 20px 40px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin-bottom: 30px;
            border-radius: 8px 8px 0 0;
            position: relative;
            text-transform: uppercase;
            user-select: none;
        }

        .header img.header-flag {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            height: 40px;
            width: auto;
            border-radius: 4px;
        }

        .receipt-container {
            max-width: 700px;
            margin: 0 auto 40px auto;
            background: white;
            border: 2px solid #078930;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(7, 137, 48, 0.2);
            padding: 25px 40px;
            position: relative;
        }

        .receipt-container::before {
            content: "";
            display: block;
            height: 6px;
            width: 100%;
            background: linear-gradient(to right, #078930, #fcd116, #e8112d);
            border-radius: 6px 6px 0 0;
            position: absolute;
            top: 0;
            left: 0;
        }

        .content p {
            margin: 7px 0;
            font-size: 10px;
            line-height: 1.4;
        }

        .stamp {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.6;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: white;
            background-color: #078930;
            padding: 8px 0;
            border-radius: 1px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/ethiopian_flag.png') }}" alt="Flag" class="header-flag" />
        Ethiopian Digital Trading and Market Control System
    </div>

    <!-- Receipt -->
    <div class="receipt-container">

        <div class="content">
            <!-- Shop Owner Info -->
            <div style="margin-bottom: 10px; border-bottom: 2px dashed #ccc; padding-bottom: 5px;">
                <h3 style="font-weight: bold; color: #078930;">Shop Owner Information</h3>
                <p><strong>Owner Name:</strong> {{ $order->product->shop->owner->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order->product->shop->owner->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $order->product->shop->phone ?? 'N/A' }}</p>
                <p><strong>Shop Name:</strong> {{ $order->product->shop->name ?? 'N/A' }}</p>
                <p><strong>Shop TIN:</strong> {{ $order->product->shop->TIN ?? 'N/A' }}</p>
            </div>

            <!-- Receipt Details -->
            <h2>Order Receipt</h2>
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Customer Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
            <p><strong>Product:</strong> {{ $order->product->name ?? 'N/A' }}</p>
            <p><strong>Product Category:</strong> {{ $order->product->category->name ?? 'N/A' }}</p>
            <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
            <p><strong>Total Price:</strong> {{ number_format($order->total_price, 2) }} ETB</p>

            @php
                $taxRate = $order->product->tax ?? 0;
                $taxAmount = ($order->total_price * $taxRate) / 100;
                $totalWithTax = $order->total_price + $taxAmount;
            @endphp

            <p><strong>Tax ({{ $taxRate }}%):</strong> {{ number_format($taxAmount, 2) }} ETB</p>
            <p><strong>Total (incl. tax):</strong> {{ number_format($totalWithTax, 2) }} ETB</p>

            <p><strong>Kebele:</strong> {{ $order->product->shop->kebele->name ?? 'N/A' }}</p>
            <p><strong>Woreda:</strong> {{ $order->product->shop->kebele->woreda->name ?? 'N/A' }}</p>
            <p><strong>Zone:</strong> {{ $order->product->shop->kebele->woreda->zone->name ?? 'N/A' }}</p>
            <p><strong>Region:</strong> {{ $order->product->shop->kebele->woreda->zone->region->name ?? 'N/A' }}</p>
            <p><strong> Payment Status:</strong> {{ ucfirst($order->status) }}</p>
<p><strong>Approved By:</strong> {{ $order->approvedBy->name ?? 'N/A' }}</p>
<p><strong>Order Last Updated At:</strong> {{ $order->updated_at ? $order->updated_at->format('d M Y, H:i:s') : 'N/A' }}</p>

        </div>

        <!-- Stamp -->
        <div class="stamp">
            <img src="{{ public_path('images/stamp.png') }}" width="140" alt="Official Stamp">
        </div>

        <!-- Footer -->
        <div class="footer">
            © {{ now()->year }} Ethiopian Digital Trading and Market Control System. All rights reserved.
        </div>
    </div>

</body>
</html>
