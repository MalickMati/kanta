<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Elegant Serif Invoice</title>
    <style>
        * { box-sizing: border-box; }
        @page { size: A5 landscape; margin: 0; }
        body { font-family: 'Times New Roman', Times, serif; background: white; margin: 0; display: flex; justify-content: center; align-items: flex-start; padding: 0; }
        .canvas { width: 210mm; height: 140mm; padding: 10mm; border: 4px double #000; margin: 0 auto; background: #fffdf9; position: relative;}
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 5mm; margin-bottom: 5mm; }
        .header h1 { font-size: 24pt; font-weight: normal; margin: 0; letter-spacing: 2px; }
        .header p { font-style: italic; font-size: 10pt; margin-top: 2mm; }
        .serial-badge { position: absolute; right: 15mm; top: 15mm; font-size: 12pt; border: 1px solid #000; padding: 2mm 5mm; }
        .content { display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5mm; font-size: 11pt; }
        td { padding: 2mm 0; border-bottom: 1px dashed #ccc; }
        .col-left { width: 60%; padding-right: 5mm; }
        .col-right { width: 38%; }
        .qr-box { text-align: center; margin-top: 5mm; }
        .totals { margin-top: 5mm; border: 2px solid #000; padding: 3mm; text-align: center; }
        .totals h2 { margin: 0; font-size: 16pt; }
    </style>
</head>
<body>
    <div class="canvas">
        <div class="serial-badge">No. {{ sprintf('%05d', $record->id) }}</div>
        <div class="header">
            <h1>AL HAMAD KANTA</h1>
            <p>Usman Rice Mill, Hafizabad Road, Vanike Tarar</p>
        </div>
        <div class="content">
            <div class="col-left">
                <table>
                    <tr><td><strong>Customer:</strong></td><td style="text-align:right">{{ $record->party }}</td></tr>
                    <tr><td><strong>Vehicle No:</strong></td><td style="text-align:right">{{ $record->vehicle_number }}</td></tr>
                    <tr><td><strong>Material:</strong></td><td style="text-align:right">{{ $record->description ?: '-' }}</td></tr>
                    <tr><td><strong>1st Weight:</strong></td><td style="text-align:right">{{ number_format((float)$record->first_weight, 2) }} kg</td></tr>
                    <tr><td><strong>2nd Weight:</strong></td><td style="text-align:right">{{ $record->second_weight ? number_format((float)$record->second_weight, 2) . ' kg' : 'Pending' }}</td></tr>
                </table>
            </div>
            <div class="col-right">
                <table>
                    <tr><td><strong>Date In:</strong></td><td style="text-align:right">{{ date('d M Y', strtotime($record->first_date)) }}</td></tr>
                    <tr><td><strong>Time In:</strong></td><td style="text-align:right">{{ date('h:i A', strtotime($record->first_date)) }}</td></tr>
                    <tr><td><strong>Date Out:</strong></td><td style="text-align:right">{{ $record->second_date ? date('d M Y', strtotime($record->second_date)) : '-' }}</td></tr>
                    <tr><td><strong>Time Out:</strong></td><td style="text-align:right">{{ $record->second_date ? date('h:i A', strtotime($record->second_date)) : '-' }}</td></tr>
                </table>
                <div class="qr-box">
                    {!! $qrCode !!}
                </div>
            </div>
        </div>
        <div class="totals">
            <h2>NET WEIGHT: {{ $record->net_weight ? number_format((float)$record->net_weight, 2) . ' kg' : 'TBD' }}</h2>
            <p style="margin-top:2mm;">Amount Paid: Rs. {{ number_format((float)$record->amount, 2) }} | Operator: {{ $user->phone ?? 'N/A' }}</p>
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
