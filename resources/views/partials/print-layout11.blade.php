<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Large Typography Layout</title>
    <style>
        * { box-sizing: border-box; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif; letter-spacing: 1px;}
        @page { size: A5 landscape; margin: 0; }
        body { margin: 0; background: #fff; display: flex; justify-content: center; align-items: flex-start; }
        .sheet { width: 210mm; height: 148mm; padding: 10mm; margin: 0 auto; }
        .header { text-align: center; border-bottom: 4px solid #000; padding-bottom: 2mm; margin-bottom: 5mm; font-family: Arial, sans-serif;}
        .header h1 { font-size: 26pt; margin: 0; letter-spacing: 0; font-weight: 900; text-transform: uppercase;}
        .header p { font-size: 10pt; margin: 0; font-weight: bold;}
        .big-party { font-size: 32pt; text-align: center; text-transform: uppercase; margin-bottom: 2mm; line-height: 1; }
        .big-vehicle { font-size: 24pt; text-align: center; color: #555; margin-bottom: 5mm; }
        .grid { display: flex; justify-content: space-between; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 5mm 0; }
        .w-col { flex: 1; text-align: center; font-family: Arial, sans-serif; }
        .w-col .lbl { font-size: 9pt; font-weight: bold; color: #666; text-transform: uppercase; margin-bottom: 2mm;}
        .w-col .val { font-size: 20pt; font-weight: 900; font-family: Impact, sans-serif;}
        .net-col { border-left: 2px solid #000; border-right: 2px solid #000; }
        .net-col .val { font-size: 28pt; }
        .meta { display: flex; justify-content: space-between; font-family: Arial, sans-serif; font-size: 9pt; font-weight: bold; margin-top: 5mm;}
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <h1>AL HAMAD KANTA</h1>
            <p>Usman Rice Mill, Hafizabad Road, Vanike Tarar | ID: {{ $record->id }}</p>
        </div>
        
        <div class="big-party">{{ $record->party }}</div>
        <div class="big-vehicle">{{ $record->vehicle_number }}</div>

        <div class="grid">
            <div class="w-col">
                <div class="lbl">First Weight</div>
                <div class="val">{{ $record->first_weight }}</div>
            </div>
            <div class="w-col net-col">
                <div class="lbl">Net Weight (KG)</div>
                <div class="val">{{ $record->net_weight ?: '---' }}</div>
            </div>
            <div class="w-col">
                <div class="lbl">Second Weight</div>
                <div class="val">{{ $record->second_weight ?: '---' }}</div>
            </div>
        </div>

        <div class="meta">
            <div>
                IN: {{ $record->first_date }}<br>
                OUT: {{ $record->second_date ?: 'PENDING' }}
            </div>
            <div>
                {!! $qrCode !!}
            </div>
            <div style="text-align: right;">
                AMT: RS. {{ $record->amount }}<br>
                OP: {{ $user->phone ?? 'SYS' }}
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
