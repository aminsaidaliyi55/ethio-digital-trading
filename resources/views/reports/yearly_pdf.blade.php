<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yearly Orders Report - {{ now()->year }}</title>
    @include('reports.partials.pdf-style')
</head>
<body>
    <h1>Yearly Orders Report</h1>
    <div class="header-info">
        <span><strong>Year:</strong> {{ now()->year }}</span>
        <span><strong>Total Approved Orders:</strong> {{ $approvedOrders->count() }}</span>
    </div>
    @include('reports.partials.orders-table')
    <p><em>Report generated on {{ now()->format('Y-m-d H:i:s') }}</em></p>
</body>
</html>
