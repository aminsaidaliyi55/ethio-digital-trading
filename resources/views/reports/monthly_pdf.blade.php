<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Orders Report - {{ now()->format('F Y') }}</title>
    @include('reports.partials.pdf-style')
</head>
<body>
    <h1>Monthly Orders Report</h1>
    <div class="header-info">
        <span><strong>Month:</strong> {{ now()->format('F Y') }}</span>
<span><strong>Total Approved Orders:</strong> {{ $approvedOrders->count() }}</span>
    </div>
    @include('reports.partials.orders-table')
    <p><em>Report generated on {{ now()->format('Y-m-d H:i:s') }}</em></p>
</body>
</html>
