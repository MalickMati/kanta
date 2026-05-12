<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Two Tone Layout</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        @page { size: A5 landscape; margin: 0; }
        body { margin: 0; background: #fff; display: flex; justify-content: center; align-items: flex-start; }
        .sheet { width: 210mm; height: 148mm; display: flex; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin: 0 auto;}
        .left-tone { width: 35%; background: #2c3e50; color: white; padding: 10mm; display: flex; flex-direction: column; justify-content: space-between; }
        .right-tone { width: 65%; background: white; padding: 10mm; display: flex; flex-direction: column; }
        .brand h2 { margin: 0 0 2mm 0; font-size: 16pt; color: #ecf0f1; }
        .brand p { margin: 0; font-size: 8pt; color: #bdc3c7; line-height: 1.4; }
        .meta { margin-top: 10mm; }
        .meta-item { margin-bottom: 4mm; }
        .meta-label { font-size: 8pt; color: #95a5a6; text-transform: uppercase; letter-spacing: 1px; }
        .meta-value { font-size: 11pt; font-weight: bold; }
        .main-data { flex: 1; }
        .data-row { display: flex; padding: 4mm 0; border-bottom: 2px solid #f1f2f6; }
        .data-label { width: 40%; color: #7f8c8d; font-size: 10pt; font-weight: bold; }
        .data-value { width: 60%; color: #2c3e50; font-size: 12pt; font-weight: 600; text-align: right; }
        .weight-row { display: flex; justify-content: space-between; margin-top: 5mm; }
        .w-card { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 4mm; width: 31%; text-align: center; }
        .w-card span { display: block; font-size: 8pt; color: #7f8c8d; margin-bottom: 2mm; text-transform: uppercase;}
        .w-card strong { font-size: 12pt; color: #2980b9; }
        .w-card.net { background: #e8f4fd; border-color: #3498db; }
        .w-card.net strong { color: #e74c3c; font-size: 14pt; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="left-tone">
            <div class="brand">
                <h2>AL HAMAD KANTA</h2>
                <p>Usman Rice Mill<br>Hafizabad Rd, Vanike Tarar</p>
                <div style="background: rgba(255,255,255,0.1); padding: 2mm; display: inline-block; border-radius: 4px; margin-top: 4mm;">
                    <span style="font-size: 14pt; font-weight: bold;"># {{ str_pad($record->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
            
            <div class="meta">
                <div class="meta-item">
                    <div class="meta-label">Time In</div>
                    <div class="meta-value">{{ date('d/m/y H:i', strtotime($record->first_date)) }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Time Out</div>
                    <div class="meta-value">{{ $record->second_date ? date('d/m/y H:i', strtotime($record->second_date)) : '---' }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Operator</div>
                    <div class="meta-value">{{ $user->phone ?? 'System' }}</div>
                </div>
            </div>

            <div class="qr" style="background:#fff; padding:2mm; border-radius:4px; width:fit-content;">
                {!! $qrCode !!}
            </div>
        </div>
        <div class="right-tone">
            <div class="main-data">
                <div class="data-row">
                    <div class="data-label">Customer Name</div>
                    <div class="data-value">{{ $record->party }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Vehicle Registration</div>
                    <div class="data-value">{{ $record->vehicle_number }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Material / Description</div>
                    <div class="data-value">{{ $record->description ?: 'N/A' }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Amount Paid</div>
                    <div class="data-value">Rs. {{ number_format((float)$record->amount, 2) }}</div>
                </div>
            </div>

            <div class="weight-row">
                <div class="w-card">
                    <span>1st Weight</span>
                    <strong>{{ $record->first_weight }} kg</strong>
                </div>
                <div class="w-card">
                    <span>2nd Weight</span>
                    <strong>{{ $record->second_weight ?: '---' }} kg</strong>
                </div>
                <div class="w-card net">
                    <span>Net Weight</span>
                    <strong>{{ $record->net_weight ?: '---' }} kg</strong>
                </div>
            </div>
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
