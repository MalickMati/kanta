<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dotted Matrix Layout</title>
    <style>
        * { box-sizing: border-box; }
        @page { size: A5 landscape; margin: 0; }
        body { margin: 0; display: flex; justify-content: center; align-items: flex-start; background: #fff; }
        .sheet { width: 210mm; height: 140mm; padding: 6mm 10mm; background: #fdfdfd; font-family: 'Courier New', Courier, monospace; letter-spacing: 0.5px; margin: 0 auto;}
        .center { text-align: center; }
        .title { font-size: 14pt; font-weight: bold; margin-bottom: 2mm;}
        .sub { font-size: 9pt; line-height: 1.2; }
        .divider { width: 100%; border-bottom: 2px dashed #333; margin: 3mm 0; }
        .kv-row { display: flex; justify-content: space-between; font-size: 10pt; margin-bottom: 1mm; }
        .kv-row .k { font-weight: bold; }
        .big-weight { font-size: 14pt; padding: 3mm 0; margin-top: 2mm; border: 2px dashed #000; display: flex; justify-content: space-around; background: #fafafa;}
        .big-weight div { text-align: center; }
        .lbl { font-size: 9pt; display: block; margin-bottom: 1mm;}
        .val { font-weight: bold; }
        .footer { margin-top: 2mm; text-align: center; font-size: 9pt; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="center title">*** AL HAMAD KANTA ***</div>
        <div class="center sub">
            USMAN RICE MILL, HAFIZABAD ROAD<br>
            VANIKE TARAR | Ph: 0315-5568175
        </div>
        <div class="divider"></div>
        
        <div class="kv-row"><span class="k">TICKET NO :</span><span class="v">{{ str_pad($record->id, 6, '0', STR_PAD_LEFT) }}</span></div>
        <div class="kv-row"><span class="k">OPERATOR  :</span><span class="v">{{ $user->phone ?? 'SYS' }}</span></div>
        
        <div class="divider"></div>
        
        <div class="kv-row"><span class="k">CUSTOMER  :</span><span class="v" style="text-transform:uppercase;">{{ $record->party }}</span></div>
        <div class="kv-row"><span class="k">VEHICLE # :</span><span class="v" style="text-transform:uppercase;">{{ $record->vehicle_number }}</span></div>
        <div class="kv-row"><span class="k">MATERIAL  :</span><span class="v" style="text-transform:uppercase;">{{ $record->description ?: 'NONE' }}</span></div>
        
        <div class="divider"></div>
        
        <div class="kv-row"><span class="k">DATE IN   :</span><span class="v">{{ $record->first_date }}</span></div>
        <div class="kv-row"><span class="k">DATE OUT  :</span><span class="v">{{ $record->second_date ?: '------PENDING------' }}</span></div>
        
        <div class="big-weight">
            <div><span class="lbl">GROSS</span><span class="val">{{ $record->first_weight }} KG</span></div>
            <div><span class="lbl">TARE</span><span class="val">{{ $record->second_weight ?: '---' }} KG</span></div>
            <div><span class="lbl">NET</span><span class="val">{{ $record->net_weight ?: '---' }} KG</span></div>
        </div>
        
        <div class="kv-row" style="margin-top:4mm;"><span class="k">CASH PAID :</span><span class="v">RS. {{ $record->amount }}</span></div>
        
        <div class="divider"></div>
        <div class="footer">
            --- THANK YOU FOR YOUR BUSINESS ---<br>
            <div style="margin-top:3mm; display:flex; justify-content:center;">
                {!! $qrCode !!}
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
