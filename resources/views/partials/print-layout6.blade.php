<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dark Header Layout</title>
    <style>
        * { box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        @page { size: A5 landscape; margin: 0; }
        body { margin: 0; background: #fff; display: flex; justify-content: center; align-items: flex-start; }
        .sheet { width: 210mm; height: 148mm; position: relative; border: 1px solid #ddd; margin: 0 auto; }
        .header { background: #1a1a1a; color: #fff; padding: 8mm; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 18pt; letter-spacing: 1px; }
        .header p { margin: 2mm 0 0; color: #ccc; font-size: 9pt; }
        .serial { background: #e74c3c; color: #fff; padding: 2mm 4mm; border-radius: 4px; font-weight: bold; font-size: 12pt; }
        .content { padding: 8mm; display: flex; gap: 6mm; }
        .main-info { flex: 1; }
        .main-info table { width: 100%; border-collapse: collapse; }
        .main-info th, .main-info td { padding: 3mm 2mm; border-bottom: 1px solid #eee; text-align: left; font-size: 10pt; }
        .main-info th { color: #666; width: 40%; }
        .side-panel { width: 60mm; background: #f9f9f9; padding: 4mm; border-radius: 8px; border: 1px solid #eee; text-align: center; }
        .weight-block { margin-bottom: 4mm; padding: 3mm; background: #fff; border: 1px solid #eee; border-radius: 4px; }
        .weight-block span { display: block; font-size: 8pt; color: #888; text-transform: uppercase; }
        .weight-block strong { font-size: 14pt; color: #222; }
        .highlight { border-color: #2ecc71; background: #f0fdf4; }
        .highlight strong { color: #27ae60; }
        .footer { position: absolute; bottom: 8mm; left: 8mm; right: 8mm; display: flex; justify-content: space-between; align-items: flex-end; border-top: 2px solid #1a1a1a; padding-top: 3mm;}
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <div>
                <h1>AL HAMAD KANTA</h1>
                <p>Usman Rice Mill, Hafizabad Road, Vanike Tarar</p>
            </div>
            <div class="serial">ID: {{ $record->id }}</div>
        </div>
        <div class="content">
            <div class="main-info">
                <table>
                    <tr><th>Customer</th><td><strong>{{ $record->party }}</strong></td></tr>
                    <tr><th>Vehicle</th><td>{{ $record->vehicle_number }}</td></tr>
                    <tr><th>Material</th><td>{{ $record->description ?: '-' }}</td></tr>
                    <tr><th>Time In</th><td>{{ $record->first_date }}</td></tr>
                    <tr><th>Time Out</th><td>{{ $record->second_date ?: 'Pending' }}</td></tr>
                </table>
            </div>
            <div class="side-panel">
                <div class="weight-block">
                    <span>Gross Weight</span>
                    <strong>{{ $record->first_weight }} KG</strong>
                </div>
                <div class="weight-block">
                    <span>Tare Weight</span>
                    <strong>{{ $record->second_weight ?: '---' }} KG</strong>
                </div>
                <div class="weight-block highlight">
                    <span>Net Weight</span>
                    <strong>{{ $record->net_weight ?: '---' }} KG</strong>
                </div>
                <div style="margin-top: 5mm;">
                    {!! $qrCode !!}
                </div>
            </div>
        </div>
        <div class="footer">
            <div style="font-size: 10pt;"><strong>Operator:</strong> {{ $user->phone ?? 'N/A' }}</div>
            <div style="font-size: 12pt; font-weight: bold;">Amount: Rs. {{ $record->amount }}</div>
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
