<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Boxed Wireframe Layout</title>
    <style>
        * { box-sizing: border-box; font-family: monospace; }
        @page { size: A5 landscape; margin: 0; }
        body { margin: 0; display: flex; justify-content: center; align-items: flex-start; background: #fff; }
        .sheet { width: 210mm; height: 148mm; padding: 10mm; margin: 0 auto;}
        .wrapper { border: 2px solid #000; height: 100%; display: flex; flex-direction: column; }
        .header { border-bottom: 2px solid #000; text-align: center; padding: 4mm; background: #f5f5f5;}
        .header h1 { margin: 0; font-size: 20pt; text-transform: uppercase; letter-spacing: 3px;}
        .header p { margin: 1mm 0 0; font-size: 9pt; }
        .row-grid { display: flex; border-bottom: 2px solid #000; }
        .box { border-right: 2px solid #000; padding: 3mm; flex: 1; display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start;}
        .box:last-child { border-right: none; }
        .lbl { font-size: 8pt; text-transform: uppercase; color: #555; margin-bottom: 2mm;}
        .val { font-size: 12pt; font-weight: bold; }
        .large-val { font-size: 18pt; font-weight: bold; text-align: center; padding: 4mm 0;}
        .net-box { flex: 2; background: #fdfdfd; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="wrapper">
            <div class="header">
                <h1>AL HAMAD KANTA</h1>
                <p>USMAN RICE MILL, HAFIZABAD ROAD, VANIKE TARAR &bull; RX: {{ $record->id }}</p>
            </div>
            
            <div class="row-grid">
                <div class="box" style="flex:2;">
                    <div class="lbl">Customer</div>
                    <div class="val">{{ $record->party }}</div>
                </div>
                <div class="box">
                    <div class="lbl">Vehicle</div>
                    <div class="val">{{ $record->vehicle_number }}</div>
                </div>
            </div>

            <div class="row-grid">
                <div class="box">
                    <div class="lbl">Material</div>
                    <div class="val">{{ $record->description ?: 'NONE' }}</div>
                </div>
                <div class="box">
                    <div class="lbl">Time In</div>
                    <div class="val">{{ $record->first_date }}</div>
                </div>
                <div class="box">
                    <div class="lbl">Time Out</div>
                    <div class="val">{{ $record->second_date ?: 'PENDING' }}</div>
                </div>
            </div>

            <div class="row-grid" style="flex:1;">
                <div class="box">
                    <div class="lbl">First Weight</div>
                    <div class="large-val">{{ $record->first_weight }} KG</div>
                </div>
                <div class="box">
                    <div class="lbl">Second Weight</div>
                    <div class="large-val">{{ $record->second_weight ?: '---' }} KG</div>
                </div>
                <div class="box net-box">
                    <div class="lbl">Final Net Weight</div>
                    <div class="large-val" style="font-size: 24pt;">{{ $record->net_weight ?: '---' }} KG</div>
                </div>
            </div>

            <div class="row-grid" style="border-bottom:none;">
                <div class="box">
                    <div class="lbl">Charges</div>
                    <div class="val">PKR {{ $record->amount }}</div>
                </div>
                <div class="box">
                    <div class="lbl">Operator</div>
                    <div class="val">{{ $user->phone ?? 'SYS' }}</div>
                </div>
                <div class="box" style="text-align:center; padding: 2mm;">
                    {!! $qrCode !!}
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
