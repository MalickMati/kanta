<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Summary Report - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }

        body {
            background: white;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 0;
        }

        @page {
            size: 210mm 148mm; /* Receipt paper print size matching receipt layout */
            margin: 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card {
                border: none !important;
                box-shadow: none !important;
                width: 100% !important;
                padding: 10px 15px !important;
            }
        }

        .no-print {
            position: fixed;
            top: 15px;
            right: 15px;
            background: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn-print {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-print:hover {
            background-color: #1d4ed8;
        }

        .receipt-card {
            width: 210mm;
            min-height: 148mm;
            background: white;
            padding: 15px 20px;
            border: 1px dashed #bbb;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000;
        }

        .header h2 {
            font-size: 11px;
            color: #444;
            font-weight: normal;
            margin-top: 2px;
        }

        .report-subtitle {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 4px;
        }

        .total-vehicles-bar {
            background: #f1f5f9;
            border: 1.5px solid #000;
            border-radius: 6px;
            padding: 6px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .summary-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .section-box {
            border: 1px solid #000;
            border-radius: 6px;
            padding: 8px 10px;
            background: #fafafa;
        }

        .section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 6px;
            color: #000;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding: 3px 0;
            border-bottom: 1px dashed #ddd;
        }

        .stat-row:last-child {
            border-bottom: none;
        }

        .stat-row strong {
            color: #000;
        }

        .earnings-box {
            grid-column: span 2;
            background: #f0fdf4;
            border-color: #16a34a;
        }

        .grand-total-row {
            border-top: 1.5px solid #16a34a;
            margin-top: 4px;
            padding-top: 4px;
            font-size: 12px;
            font-weight: bold;
            color: #15803d;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Print Summary</button>
    </div>

    <div class="receipt-card">
        <!-- Header -->
        <div class="header">
            <h1>{{ $company->value ?? config('app.name') }}</h1>
            <h2>
                @if(isset($address) && $address->value) {{ $address->value }} @endif
                @if(isset($contact) && $contact->value) | Contact: {{ $contact->value }} @endif
            </h2>
            <div class="report-subtitle">
                SUMMARY REPORT 
                (@if(request('filter_type') == 'date')
                    Date: {{ request('from_date', 'Start') }} to {{ request('to_date') ?: \Carbon\Carbon::today()->toDateString() }}
                @elseif(request('filter_type') == 'serial')
                    Serial: #{{ request('from_serial', '1') }} to #{{ request('to_serial', 'Latest') }}
                @else
                    All Records
                @endif)
            </div>
        </div>

        <!-- Total Vehicles Bar -->
        <div class="total-vehicles-bar">
            <span>Total Vehicles Weighted:</span>
            <span>{{ number_format($totalVehicles) }}</span>
        </div>

        <!-- Summary Grid -->
        <div class="summary-container">
            <!-- Operators Breakdown -->
            <div class="section-box">
                <div class="section-title">Vehicles per Operator</div>
                @forelse($operatorSummary as $op)
                    <div class="stat-row">
                        <span>{{ $op['name'] }} ({{ $op['username'] }}):</span>
                        <strong>{{ number_format($op['count']) }}</strong>
                    </div>
                @empty
                    <div class="stat-row"><span>No operators found</span></div>
                @endforelse
            </div>

            <!-- Vehicles Breakdown -->
            <div class="section-box">
                <div class="section-title">Vehicle Types Breakdown</div>
                @forelse($vehicleTypeSummary as $vType => $count)
                    <div class="stat-row">
                        <span>{{ $vType }}:</span>
                        <strong>{{ number_format($count) }}</strong>
                    </div>
                @empty
                    <div class="stat-row"><span>No vehicles found</span></div>
                @endforelse
            </div>

            <!-- Earnings Breakdown -->
            <div class="section-box earnings-box">
                <div class="section-title" style="border-color: #16a34a; color: #15803d;">Earnings Breakdown</div>
                @forelse($operatorSummary as $op)
                    <div class="stat-row">
                        <span>Earnings ({{ $op['name'] }}):</span>
                        <strong>{{ number_format($op['earnings']) }} RS</strong>
                    </div>
                @empty
                    <div class="stat-row"><span>No earnings recorded</span></div>
                @endforelse

                <div class="stat-row grand-total-row">
                    <span>Total Earnings:</span>
                    <span>{{ number_format($totalEarnings) }} RS</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 300);
        };
        window.addEventListener("afterprint", function() {
            window.history.back();
        });
    </script>
</body>
</html>
