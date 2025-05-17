<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Orders Report - {{ now()->format('F j, Y') }}</title>
    @include('reports.partials.pdf-style')
    <style>
        .pdf-header, .pdf-footer {
            text-align: center;
            padding: 10px 0;
            color: #2c3e50;
            font-size: 14px;
            font-weight: bold;
        }

        .pdf-header {
            border-bottom: 2px solid #3498db;
            margin-bottom: 20px;
        }

        .pdf-footer {
            border-top: 2px solid #3498db;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 12px;
            color: #888;
        }

        body {
            margin-bottom: 80px; /* leave space for footer */
        }
    </style>
</head>
<body>

    <!-- System Header -->
    <div class="pdf-header">
        Ethiopian Digital Trading And Market Control System<br>
        Daily Orders Report
    </div>

    <!-- Report Info -->
    <div class="header-info">
        <span><strong>Date:</strong> {{ now()->format('F j, Y') }}</span>
        <span><strong>Total Approved Orders:</strong> {{ $approvedOrders->count() }}</span>
    </div>

    <!-- Table -->
    @include('reports.partials.orders-table')

    <!-- Generated Timestamp -->
    <p><em>Report generated on {{ now()->format('Y-m-d H:i:s') }}</em></p>

    <!-- System Footer -->
    <div class="pdf-footer">
        &copy; {{ now()->year }} Ethiopian Digital Trading And Market Control System. All rights reserved.
    </div>

</body>
</html>
