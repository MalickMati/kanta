<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rounded Cards Layout</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        @page { size: A5 landscape; margin: 0; }
        body { margin: 0; background: #fff; display: flex; justify-content: center; align-items: flex-start; }
        .sheet { width: 210mm; height: 148mm; padding: 12mm; background: #fdfdfd; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8mm; }
        .header h1 { margin: 0; font-size: 20pt; color: #4b6584; }
        .header p { margin: 1mm 0 0; font-size: 9pt; color: #778ca3; }
        .tid { background: #fc5c65; color: white; padding: 2mm 5mm; border-radius: 20px; font-weight: bold; font-size: 11pt; }
        .cards { display: grid; grid-template-columns: 1fr 1fr; gap: 6mm; margin-bottom: 6mm; }
        .card { background: white; border: 2px solid #eb3b5a; border-radius: 12px; padding: 4mm; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card.blue { border-color: #45aaf2; }
        .card.green { border-color: #2bcbba; grid-column: span 2; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 8pt; color: #a5b1c2; text-transform: uppercase; margin-bottom: 3mm; letter-spacing: 1px; font-weight: bold; }
        .row { display: flex; justify-content: space-between; margin-bottom: 2mm; font-size: 10pt; }
        .lbl { color: #778ca3; }
        .val { color: #2d98da; font-weight: bold; text-align: right;}
        .card.green .val { font-size: 14pt; color: #20bf6b; }
        .footer { font-size: 9pt; color: #a5b1c2; text-align: center; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <div>
                <h1>Al Hamad Kanta</h1>
                <p>USMAN RICE MILL, VANIKE TARAR</p>
            </div>
            <div class="tid">ID: {{ str_pad($record->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="cards">
            <div class="card blue">
                <div class="card-title">Vehicle & Customer</div>
                <div class="row"><span class="lbl">Customer</span><span class="val">{{ $record->party }}</span></div>
                <div class="row"><span class="lbl">Vehicle</span><span class="val">{{ $record->vehicle_number }}</span></div>
                <div class="row" style="margin-bottom:0;"><span class="lbl">Material</span><span class="val">{{ $record->description ?: '-' }}</span></div>
            </div>
            <div class="card">
                <div class="card-title">Timing</div>
                <div class="row"><span class="lbl">In</span><span class="val">{{ $record->first_date }}</span></div>
                <div class="row"><span class="lbl">Out</span><span class="val">{{ $record->second_date ?: 'Pending' }}</span></div>
                <div class="row" style="margin-bottom:0;"><span class="lbl">Operator</span><span class="val">{{ $user->phone ?? 'SYS' }}</span></div>
            </div>
            <div class="card green">
                <div style="flex:1;">
                    <div class="card-title" style="margin-bottom:1mm;">First Wt</div>
                    <div class="val" style="text-align:left; color:#a5b1c2;">{{ $record->first_weight }} KG</div>
                </div>
                <div style="flex:1; text-align:center;">
                    <div class="card-title" style="margin-bottom:1mm;">Second Wt</div>
                    <div class="val" style="text-align:center; color:#a5b1c2;">{{ $record->second_weight ?: '---' }} KG</div>
                </div>
                <div style="flex:1; text-align:right;">
                    <div class="card-title" style="margin-bottom:1mm; color:#20bf6b;">Net Weight</div>
                    <div class="val" style="color:#20bf6b; font-size:16pt;">{{ $record->net_weight ?: '---' }} KG</div>
                </div>
                <div style="margin-left:5mm;">
                    {!! $qrCode !!}
                </div>
            </div>
        </div>
        <div class="footer">
            Amount Paid: Rs. {{ number_format((float)$record->amount, 2) }}
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
