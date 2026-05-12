<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zebra Striped Layout</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        @page { size: A5 landscape; margin: 0; }
        body { margin: 0; background: #fff; display: flex; justify-content: center; align-items: flex-start; }
        .sheet { width: 210mm; height: 148mm; padding: 12mm; margin: 0 auto;}
        h1 { color: #2c3e50; font-size: 18pt; margin: 0; text-align: center; text-transform: uppercase;}
        p.sub { text-align: center; font-size: 9pt; color: #7f8c8d; margin-bottom: 8mm;}
        table { width: 100%; border-collapse: collapse; font-size: 10pt; }
        th, td { padding: 3mm; border: 1px solid #ddd; }
        th { background: #34495e; color: white; text-align: left; width: 25%; font-weight: normal;}
        td { background: white; color: #2c3e50; font-weight: bold;}
        tr:nth-child(even) td { background: #ecf0f1; }
        .flex-table { display: flex; justify-content: space-between; margin-top: 5mm; }
        .mini-table { width: 48%; }
        .total-box { margin-top: 5mm; background: #e8f8f5; border: 2px solid #1abc9c; padding: 4mm; text-align: center; color: #16a085; font-size: 16pt; font-weight: bold; border-radius: 4px;}
        .footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 5mm; font-size: 9pt; color: #7f8c8d;}
    </style>
</head>
<body>
    <div class="sheet">
        <h1>Al Hamad Computerized Kanta</h1>
        <p class="sub">Usman Rice Mill, Hafizabad Road, Vanike Tarar<br><b>Ticket ID: {{ $record->id }}</b></p>
        
        <table>
            <tr>
                <th>Customer Name</th>
                <td>{{ $record->party }}</td>
                <th>Vehicle Number</th>
                <td>{{ $record->vehicle_number }}</td>
            </tr>
            <tr>
                <th>Material/Item</th>
                <td>{{ $record->description ?: '-' }}</td>
                <th>Amount Received</th>
                <td>Rs. {{ number_format((float)$record->amount, 2) }}</td>
            </tr>
        </table>

        <div class="flex-table">
            <table class="mini-table">
                <tr><th>Time In</th><td>{{ date('d-m-Y h:i A', strtotime($record->first_date)) }}</td></tr>
                <tr><th>Time Out</th><td>{{ $record->second_date ? date('d-m-Y h:i A', strtotime($record->second_date)) : '-' }}</td></tr>
                <tr><th>Operator</th><td>{{ $user->phone ?? 'System' }}</td></tr>
            </table>

            <table class="mini-table">
                <tr><th>Gross (1st)</th><td>{{ $record->first_weight }} kg</td></tr>
                <tr><th>Tare (2nd)</th><td>{{ $record->second_weight ?: '---' }} kg</td></tr>
                <tr><th>Net Wt.</th><td style="color:#e74c3c;">{{ $record->net_weight ?: '---' }} kg</td></tr>
            </table>
        </div>

        <div class="total-box">
            FINAL NET WEIGHT: {{ $record->net_weight ?: 'PENDING' }} KG
        </div>

        <div class="footer">
            <div>Signature: ______________________</div>
            <div>{!! $qrCode !!}</div>
        </div>
    </div>

@if(!isset($isPreview) || !$isPreview)
    <script>
        window.onload = function() { window.print(); }
        window.addEventListener("afterprint", function() { window.history.back(); });
    </script>
@endif
</body>
</html>
