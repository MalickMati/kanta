<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Right Aligned Layout</title>
    <style>
        * { box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        @page { size: A5 landscape; margin: 0; }
        body { margin: 0; background: #fff; display: flex; justify-content: center; align-items: flex-start; }
        .sheet { width: 210mm; height: 148mm; padding: 15mm 20mm; position: relative; margin: 0 auto;}
        .brand { text-align: right; border-bottom: 2px solid #000; padding-bottom: 2mm; margin-bottom: 8mm;}
        .brand h1 { margin: 0; font-size: 22pt; font-weight: 300; letter-spacing: 2px; }
        .brand p { margin: 0; font-size: 8pt; color: #888; text-transform: uppercase; letter-spacing: 1px;}
        .row { display: flex; align-items: baseline; margin-bottom: 4mm; font-size: 11pt; }
        .lbl { font-weight: 600; color: #555; white-space: nowrap;}
        .dots { flex: 1; border-bottom: 1px dotted #ccc; margin: 0 4mm; position: relative; top: -3px;}
        .val { font-weight: bold; font-size: 14pt; color: #000; white-space: nowrap;}
        .columns { display: flex; justify-content: space-between; margin-top: 10mm; }
        .col { width: 45%; }
        .net-wt { margin-top: 8mm; text-align: right; background: #f4f4f4; padding: 5mm; border-radius: 5px;}
        .net-wt .lbl { font-size: 12pt; }
        .net-wt .val { font-size: 24pt; color: #d35400; display: block; margin-top: 2mm;}
        .qr { position: absolute; left: 20mm; bottom: 15mm; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="brand">
            <h1>AL HAMAD KANTA</h1>
            <p>Usman Rice Mill, Hafizabad Road</p>
            <p>Receipt # {{ str_pad($record->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="row">
            <span class="lbl">Customer</span><span class="dots"></span><span class="val">{{ $record->party }}</span>
        </div>
        <div class="row">
            <span class="lbl">Vehicle Registration</span><span class="dots"></span><span class="val">{{ $record->vehicle_number }}</span>
        </div>
        <div class="row">
            <span class="lbl">Material Description</span><span class="dots"></span><span class="val">{{ $record->description ?: 'N/A' }}</span>
        </div>

        <div class="columns">
            <div class="col">
                <div class="row"><span class="lbl">Time In</span><span class="dots"></span><span class="val" style="font-size:11pt;">{{ $record->first_date }}</span></div>
                <div class="row"><span class="lbl">Time Out</span><span class="dots"></span><span class="val" style="font-size:11pt;">{{ $record->second_date ?: 'Pending' }}</span></div>
                <div class="row"><span class="lbl">Charges Paid</span><span class="dots"></span><span class="val" style="font-size:11pt;">Rs. {{ $record->amount }}</span></div>
            </div>
            <div class="col">
                <div class="row"><span class="lbl">First Weight</span><span class="dots"></span><span class="val">{{ $record->first_weight }} KG</span></div>
                <div class="row"><span class="lbl">Second Weight</span><span class="dots"></span><span class="val">{{ $record->second_weight ?: '---' }} KG</span></div>
            </div>
        </div>

        <div class="net-wt">
            <span class="lbl">Total Net Weight:</span>
            <span class="val">{{ $record->net_weight ?: 'PENDING' }} KG</span>
        </div>

        <div class="qr">
            {!! $qrCode !!}
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
