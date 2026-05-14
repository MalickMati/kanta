<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Report - {{ config('app.name') }}</title>
    <style>
        :root {
            --font-family: 'Inter', system-ui, -apple-system, sans-serif;
            --text-main: #111827;
            --text-muted: #4b5563;
            --border-color: #e5e7eb;
        }

        body {
            font-family: var(--font-family);
            color: var(--text-main);
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .no-print {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            max-width: 210mm;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-print {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-print:hover {
            background-color: #4338ca;
        }

        /* A4 Page Setup */
        .page-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm;
            box-sizing: border-box;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 15px;
        }

        .report-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            table-layout: fixed; /* Ensures it fits exactly in width */
        }

        th, td {
            border: 1px solid var(--border-color);
            padding: 8px 10px;
            word-wrap: break-word;
        }

        th {
            background-color: #f9fafb;
            font-weight: 600;
            text-align: left;
            text-transform: uppercase;
            font-size: 11px;
            color: var(--text-muted);
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Repeating headers on new pages */
        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr {
            page-break-inside: avoid;
        }

        /* Set column widths so they don't overflow */
        th.col-serial { width: 10%; }
        th.col-party { width: 30%; }
        th.col-weight { width: 15%; }
        th.col-amount { width: 15%; }

        .summary-row {
            font-weight: 700;
            background-color: #f9fafb;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            body {
                background: none;
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .page-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: auto;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <div>
            <strong>Report Preview</strong> ({{ $records->count() }} records found)
        </div>
        <button class="btn-print" onclick="window.print()">Print Document</button>
    </div>

    <div class="page-container">
        <div class="report-header">
            <h1 class="report-title">{{ config('app.name') }} - Records Report</h1>
            <p class="report-subtitle">
                @if(request('filter_type') == 'date')
                    Date Range: {{ request('from_date', 'Start') }} to {{ request('to_date') ?: \Carbon\Carbon::today()->toDateString() }}
                @elseif(request('filter_type') == 'serial')
                    Serial Range: {{ request('from_serial', '1') }} to {{ request('to_serial', 'Latest') }}
                @else
                    All Records
                @endif
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="col-serial text-center">Serial #</th>
                    <th class="col-party">Party Name</th>
                    <th class="col-weight text-right">First Wt.</th>
                    <th class="col-weight text-right">Second Wt.</th>
                    <th class="col-weight text-right">Net Wt.</th>
                    <th class="col-amount text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalFirst = 0;
                    $totalSecond = 0;
                    $totalNet = 0;
                    $totalAmount = 0;
                @endphp

                @forelse($records as $r)
                    @php
                        $totalFirst += $r->first_weight;
                        $totalSecond += $r->second_weight;
                        $totalNet += $r->net_weight;
                        $totalAmount += $r->amount;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $r->id }}</td>
                        <td>{{ $r->party ?? '-' }}</td>
                        <td class="text-right">{{ number_format($r->first_weight) }}</td>
                        <td class="text-right">{{ number_format($r->second_weight) }}</td>
                        <td class="text-right">{{ number_format($r->net_weight) }}</td>
                        <td class="text-right">{{ number_format($r->amount) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No records found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
            @if($records->count() > 0)
            <tfoot>
                <tr class="summary-row">
                    <td colspan="2" class="text-right">TOTALS:</td>
                    <td class="text-right">{{ number_format($totalFirst) }}</td>
                    <td class="text-right">{{ number_format($totalSecond) }}</td>
                    <td class="text-right">{{ number_format($totalNet) }}</td>
                    <td class="text-right">{{ number_format($totalAmount) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <script>
        // Auto-print on load if needed, user requested when click print details it prints.
        // It's usually better UX to open the tab and immediately pop up print dialog.
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
