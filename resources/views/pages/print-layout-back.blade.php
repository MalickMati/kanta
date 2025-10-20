<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Al Hammad Computerized Kanta | Vanike Tarar</title>
<style>
  /* ===== Compact, borderless, no shadows ===== */
  *{box-sizing:border-box}
  html,body{
    margin:0; padding:0; background:#ffffff; color:#0f141a;
    font-family: Arial, Helvetica, sans-serif; -webkit-print-color-adjust:exact; print-color-adjust:exact;
    display:flex; justify-content:center; align-items:flex-start; padding-top:6mm;
  }
  @page{ size:A4 portrait; margin:0 }
  @media print{ body{ padding-top:0 } }

  /* --- Density controls --- */
  :root{
    --fz-s:8.6pt;
    --fz:9.2pt;
    --fz-l:12.5pt;
    --lh:1.12;
    --gap:4mm;
    --gap-s:2.5mm;
    --ink:#0f141a;
    --muted:#5a6472;
    --grad1:#0a73f0;
    --grad2:#16c1a6;
    --softA:#f4f9ff;
    --softB:#f1fff8;
    --warnA:#fff1ed;
    --warnText:#812018;
    --okA:#ecfff5;
    --okText:#0e5a33;
  }

  /* Ticket block: half-A4 width, very compact height */
  .ticket{
    width:148mm;      /* A5 width */
    padding:0 6mm 6mm 6mm;
    line-height:var(--lh);
    text-align:center;
  }

  /* Slim hero band (12mm tall) */
  .hero{
    height:12mm; position:relative;
    background:linear-gradient(90deg, var(--grad1), var(--grad2));
    border-radius:8px; overflow:clip;
  }
  .hero::before{
    content:""; position:absolute; inset:0;
    background:
      radial-gradient(18mm 8mm at 22% 60%, rgba(255,255,255,.55) 0 55%, transparent 56%),
      radial-gradient(16mm 7mm at 70% 30%, rgba(255,255,255,.35) 0 55%, transparent 56%);
    mix-blend-mode:soft-light;
  }
  .brand{
    position:absolute; inset:0; display:flex; align-items:center; justify-content:center; gap:8px;
    color:#ffffff;
  }
  .badge{
    width:12mm; height:12mm; border-radius:5px;
    background:linear-gradient(135deg, #ffd664, #ff7aa2);
    display:flex; align-items:center; justify-content:center;
    font-weight:900; letter-spacing:.4px; color:#1b1b1b; font-size:8.8pt;
  }
  .title{ text-align:left }
  .title h1{ margin:0; font-size:var(--fz-l) }
  .title p{ margin:1px 0 0 0; font-size:8pt; opacity:.95 }

  /* Serial chip just below hero, tiny padding */
  .serial{ margin-top:2mm; text-align:right; font-weight:900; font-size:8.6pt; color:#0a3a8f }
  .serial span{ background:linear-gradient(90deg,#eef4ff,#f4fffb); padding:3px 8px; border-radius:999px }

  /* Sections with ultra-thin gradient rule (no borders) */
  .section{
    text-align:left; margin:var(--gap-s) 0 1.5mm 0; font-weight:800; font-size:9.6pt; color:var(--ink);
    position:relative;
  }
  .section::after{
    content:""; display:block; height:2px; margin-top:3px;
    background:linear-gradient(90deg, #004aad, #00c292, transparent 70%);
  }

  /* Grid: two columns, narrow gap */
  .grid{ display:grid; grid-template-columns:1.05fr .95fr; gap:6mm; margin-top:1.5mm }

  /* Key-value tables with zero borders, tight rows */
  .kv{ width:100%; border-collapse:collapse; font-size:var(--fz) }
  .kv td{ padding:2px 0 }               /* very tight row */
  .k{ width:38%; color:var(--muted); font-weight:700; font-size:var(--fz-s) }
  .v{ color:var(--ink); font-weight:800 }

  /* Soft micro-panels instead of boxes */
  .soft{ padding:4px 5px; border-radius:7px; background:linear-gradient(180deg,var(--softA),#f0f6ff) }
  .soft.alt{ background:linear-gradient(180deg,var(--softB),#effeff) }

  /* Chips line for bags */
  .chips{ display:flex; flex-wrap:wrap; gap:6px; margin-top:3px }
  .chip{
    padding:4px 8px; border-radius:999px; font-weight:800; font-size:8.8pt;
    background:linear-gradient(180deg,#fff2e0,#ffe8c9); color:#5a2a00;
  }
  .chip.small{ background:linear-gradient(180deg,#eef4ff,#e6f0ff); color:#0a2a66 }

  /* Weights: condensed */
  .weights{ display:grid; gap:4px; margin-top:2mm }
  .w{ display:grid; grid-template-columns:38% 62%; align-items:center; gap:6px }
  .w .label{ font-size:var(--fz); font-weight:800; color:#1b2430 }
  .w .value{
    font-size:11pt; font-weight:900; text-align:center; padding:6px 0; border-radius:8px;
    background:linear-gradient(180deg,#f3f8ff,#e8f1ff); color:#0d1b3a;
  }
  .w.net .value{ background:linear-gradient(180deg,var(--warnA),#ffe5dd); color:var(--warnText) }

  /* Amount pill, small but prominent */
  .amount{ margin-top:4mm; display:flex; justify-content:center }
  .pill{
    padding:7px 12px; border-radius:999px; font-weight:900; font-size:11pt;
    background:linear-gradient(180deg,var(--okA),#dff7eb); color:var(--okText);
  }

  /* Footer micro text */
  .foot{ margin-top:4mm; font-size:8pt; color:#6a7380; text-align:center }
</style>
<script>
  window.onload = function(){ window.print(); }
  window.addEventListener("afterprint", () => { window.history.back(); });
</script>
</head>
<body>
  <div class="ticket">
    <!-- Compact hero -->
    <div class="hero">
      <div class="brand">
        <div class="title">
          <h1 style="text-align: center;">AL HAMAD COMPUTERIZED KANTA</h1>
          <p>USMAN RICE MILL HAFIZABAD ROAD, VANIKE TARAR (0315-5568175)</p>
        </div>
      </div>
    </div>

    <div class="serial"><span>Serial: {{ $record->id }}</span></div>

    <!-- Content -->
    <div class="section">Customer & Vehicle</div>
    <div class="grid">
      <table class="kv">
        <tr><td class="k">Customer</td><td class="v">{{ $record->party }}</td></tr>
        <tr><td class="k">Vehicle #</td><td class="v">{{ $record->vehicle_number }}</td></tr>
        <tr><td class="k">Operator #</td><td class="v">&nbsp; {{ $user->phone }}</td></tr>
      </table>

      <div>
        <div class="soft">
          <table class="kv">
            <tr><td class="k">1st Date</td><td class="v">{{ date('Y-m-d', strtotime($record->first_date)) }}</td></tr>
            <tr><td class="k">1st Time</td><td class="v">{{ date('H:i:s', strtotime($record->first_date)) }}</td></tr>
          </table>
        </div>
        <div class="soft alt" style="margin-top:4px">
          <table class="kv">
            <tr><td class="k">2nd Date</td><td class="v">{{ $record->second_date ? date('Y-m-d', strtotime($record->second_date)) : '-' }}</td></tr>
            <tr><td class="k">2nd Time</td><td class="v">{{ $record->second_date ? date('H:i:s', strtotime($record->second_date)) : '-' }}</td></tr>
          </table>
        </div>
      </div>
    </div>

    <div class="section">Description</div>
    <table class="kv">
      <tr><td class="k">Material</td><td class="v">{{ $record->description }}</td></tr>
    </table>
    <div class="chips">
      <div class="chip small">Unit 40 Kg</div>
      <div class="chip">Mound: {{ $record->net_weight ? number_format($record->net_weight / 40, 1) : '-' }}</div>
    </div>

    <div class="section">Weights</div>
    <div class="weights">
      <div class="w">
        <div class="label">1st Weight</div>
        <div class="value">{{ $record->first_weight }} KG</div>
      </div>
      <div class="w">
        <div class="label">2nd Weight</div>
        <div class="value">{{ $record->second_weight ? "$record->second_weight KG" : '-' }}</div>
      </div>
      <div class="w net">
        <div class="label">Net Weight</div>
        <div class="value">{{ $record->net_weight ? "$record->net_weight KG" : '-' }}</div>
      </div>
    </div>

    <div class="amount"><div class="pill">{{ $record->amount }} Rs. Received With Thanks</div></div>

    <!-- <div class="foot">Keep this slip for your record. Reprints available on request.</div> -->
  </div>
</body>
</html>
