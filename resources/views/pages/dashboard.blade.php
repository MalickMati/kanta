@extends('layouts.app')

@section('title', config('app.name') . ' - Dashboard')

@section('css')
  <style>
    /* Surface & motion tokens */
    :root {
      --radius-lg: 16px;
      --radius: 12px;
      --radius-sm: 10px;
      --ring: 0 0 0 2px rgba(99, 102, 241, 0.15);
      --glass: linear-gradient(180deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.35));
      --glass-dark: linear-gradient(180deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02));
    }

    .dashboard-content {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1.25rem;
      align-items: stretch;
    }

    .two-col-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.25rem;
    }

    /* Card */
    .card {
      position: relative;
      background: var(--bg-secondary);
      border-radius: var(--radius-lg);
      padding: 1.25rem;
      border: 1px solid var(--border);
      box-shadow:
        0 1px 2px rgba(0, 0, 0, 0.06),
        0 8px 24px rgba(0, 0, 0, 0.06);
      transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease, background-color .22s ease;
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      overflow: hidden;
      isolation: isolate;
    }

    /* subtle sheen */
    .card::after {
      content: "";
      position: absolute;
      inset: 0;
      pointer-events: none;
      background: radial-gradient(1200px 300px at -10% -10%, rgba(99, 102, 241, 0.12), transparent 40%),
        radial-gradient(1200px 300px at 110% 110%, rgba(236, 72, 153, 0.10), transparent 40%);
      opacity: .6;
      z-index: 0;
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow:
        0 3px 10px rgba(0, 0, 0, 0.06),
        0 16px 40px rgba(0, 0, 0, 0.08);
      border-color: color-mix(in oklab, var(--accent) 25%, var(--border));
    }

    /* Stat cards */
    .stat-card {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1.25rem 1.1rem;
    }

    .stat-icon {
      width: 56px;
      height: 56px;
      border-radius: 14px;
      display: grid;
      place-items: center;
      font-size: 1.35rem;
      color: #fff;
      box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.18), 0 8px 20px rgba(0, 0, 0, 0.12);
      flex: 0 0 auto;
    }

    .icon-1 { background: linear-gradient(135deg, #3b82f6, #8b5cf6); }
    .icon-2 { background: linear-gradient(135deg, #10b981, #06b6d4); }
    .icon-3 { background: linear-gradient(135deg, #f59e0b, #ef4444); }
    .icon-4 { background: linear-gradient(135deg, #8b5cf6, #ec4899); }

    .stat-info {
      position: relative;
      z-index: 1;
    }

    .stat-info h3 {
      font-size: clamp(1.35rem, 1.1rem + 0.6vw, 1.65rem);
      font-weight: 800;
      letter-spacing: -0.01em;
      margin: 0 0 0.15rem 0;
      line-height: 1.1;
    }

    .stat-info p {
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin: 0;
    }

    .section-title {
      font-size: 1.1rem;
      font-weight: 700;
      letter-spacing: -0.01em;
      margin: 0 0 1rem 0;
      position: relative;
      z-index: 1;
    }

    /* Charts */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
        z-index: 1;
    }

    /* Tables */
    .table-container {
        width: 100%;
        overflow-x: auto;
        z-index: 1;
        position: relative;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .data-table th, .data-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid color-mix(in oklab, var(--border) 60%, transparent);
        font-size: 0.95rem;
    }
    .data-table th {
        font-weight: 600;
        color: var(--text-secondary);
        background: color-mix(in oklab, var(--bg-primary) 50%, transparent);
        border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    }
    .data-table tr:last-child td {
        border-bottom: none;
    }
    .data-table tr:hover td {
        background: color-mix(in oklab, var(--bg-primary) 80%, transparent);
    }
    .badge {
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        background: color-mix(in oklab, var(--accent) 15%, transparent);
        color: var(--accent);
    }

    /* Recent activity */
    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 0.85rem;
      position: relative;
      z-index: 1;
    }

    .activity-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 0.9rem;
      border-radius: var(--radius);
      background:
        color-mix(in oklab, var(--bg-primary) 88%, transparent);
      border: 1px solid color-mix(in oklab, var(--border) 85%, transparent);
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
      transition: transform .18s ease, background-color .18s ease, border-color .18s ease;
    }

    .activity-item:hover {
      transform: translateY(-1px);
      border-color: color-mix(in oklab, var(--accent) 18%, var(--border));
    }

    .activity-icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: grid;
      place-items: center;
      background: color-mix(in oklab, var(--accent) 12%, transparent);
      color: var(--accent);
      box-shadow: inset 0 0 0 1px color-mix(in oklab, var(--accent) 24%, transparent);
      flex: 0 0 auto;
    }

    .activity-details h4 {
      font-size: 0.98rem;
      margin: 0 0 0.15rem 0;
      font-weight: 700;
    }

    .activity-details p {
      font-size: 0.88rem;
      color: var(--text-secondary);
      margin: 0;
    }

    .activity-time {
      margin-left: auto;
      font-size: 0.78rem;
      color: var(--text-secondary);
      white-space: nowrap;
      opacity: .9;
    }

    /* Light/Glass tweak: respect theme */
    @media (prefers-color-scheme: light) {
      .card { background: var(--glass); }
    }
    @media (prefers-color-scheme: dark) {
      .card { background: var(--glass-dark); }
    }

    /* Responsive */
    @media (max-width: 1280px) {
      .dashboard-content { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 1024px) {
      .two-col-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
      .dashboard-content { grid-template-columns: 1fr; gap: 1rem; }
      .stat-card { flex-direction: row; }
    }
  </style>
@endsection

@section('main-content')
  <x-header heading="Dashboard" para="Welcome back, {{ Auth::user()->username }}! Here's your {{ $isAdmin ? 'administrative ' : '' }}overview." />
  
  <div class="dashboard-content" style="margin-bottom: 1.25rem;">
    <div class="card stat-card">
      <div class="stat-icon icon-1">
        <i class="svg-icon"><x-icons name="weight" /></i>
      </div>
      <div class="stat-info">
        <h3>{{ $today_weight ?? '-' }}</h3>
        <p>Today's Weighings</p>
      </div>
    </div>

    <div class="card stat-card">
      <div class="stat-icon icon-2">
        <i class="svg-icon"><x-icons name="calender"/></i>
      </div>
      <div class="stat-info">
        <h3>{{ $weekly_weight ?? '-' }}</h3>
        <p>Weekly Weighings</p>
      </div>
    </div>

    <div class="card stat-card">
      <div class="stat-icon icon-3">
        <i class="svg-icon"><x-icons name="calender"/></i>
      </div>
      <div class="stat-info">
        <h3>{{ $monthly_weight ?? '-' }}</h3>
        <p>Monthly Weighings</p>
      </div>
    </div>

    <div class="card stat-card">
      <div class="stat-icon icon-4">
        <i class="svg-icon"><x-icons name="chart-line"/></i>
      </div>
      <div class="stat-info">
        <h3>Rs. {{ number_format($amount_month ?? 0) }}</h3>
        <p>Revenue This Month</p>
      </div>
    </div>
  </div>

  <div class="two-col-grid" style="margin-bottom: 1.25rem;">
    <div class="card">
        <h2 class="section-title">7-Day Weighings Trend</h2>
        <div class="chart-container">
            <canvas id="weighingsChart"></canvas>
        </div>
    </div>
    <div class="card">
        <h2 class="section-title">7-Day Revenue Trend</h2>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
  </div>

  <div class="two-col-grid">
    @if($isAdmin)
    <div class="card" style="grid-column: span 1;">
        <h2 class="section-title">Operator Performance (This Month)</h2>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Operator</th>
                        <th>Today</th>
                        <th>Monthly Weighings</th>
                        <th>Monthly Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($operatorsData as $op)
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $op['username'] }}</div>
                        </td>
                        <td><span class="badge">{{ $op['today'] }}</span></td>
                        <td>{{ $op['month'] }}</td>
                        <td style="font-weight: 600; color: var(--success);">Rs. {{ number_format($op['revenue_month']) }}</td>
                    </tr>
                    @endforeach
                    @if(count($operatorsData) == 0)
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">No active operators found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="card recent-activity" style="{{ $isAdmin ? '' : 'grid-column: span 2;' }}">
      <h2 class="section-title">Recent Activity</h2>
      <div class="activity-list">
        @if (!empty($recent) && count($recent) > 0)
          @foreach ($recent as $record)
            <div class="activity-item">
              <div class="activity-icon">
                <i class="svg-icon"><x-icons name="weight" /></i>
              </div>
              <div class="activity-details">
                <h4>Weight Recorded @if($isAdmin) by <span style="color: var(--accent);">{{ $record->created_by }}</span> @endif</h4>
                <p>Vehicle #{{ $record->vehicle_number }} - {{ $record->first_weight }} kg</p>
              </div>
              <div class="activity-time">{{ \Carbon\Carbon::parse($record->created_at)->format('d M, h:i A') }}</div>
            </div>
          @endforeach
        @else
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">No recent activity found.</div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('script')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawChartData = {!! $chartData !!};
        
        // Setup Chart defaults
        Chart.defaults.color = getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim();
        Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
        
        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
        
        // --- Weighings Chart ---
        const ctxWeighings = document.getElementById('weighingsChart').getContext('2d');
        const weighingsGradient = ctxWeighings.createLinearGradient(0, 0, 0, 300);
        weighingsGradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // --accent
        weighingsGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
        
        new Chart(ctxWeighings, {
            type: 'line',
            data: {
                labels: rawChartData.labels,
                datasets: [{
                    label: 'Weighings',
                    data: rawChartData.weighings,
                    borderColor: '#3b82f6',
                    backgroundColor: weighingsGradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: isDarkMode ? '#1e293b' : '#fff',
                        titleColor: isDarkMode ? '#f1f5f9' : '#1e293b',
                        bodyColor: isDarkMode ? '#94a3b8' : '#64748b',
                        borderColor: isDarkMode ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        boxPadding: 4
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 0 }
                    },
                    y: {
                        grid: { color: gridColor },
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        // --- Revenue Chart ---
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const revenueGradient = ctxRevenue.createLinearGradient(0, 0, 0, 300);
        revenueGradient.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // --success
        revenueGradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: rawChartData.labels,
                datasets: [{
                    label: 'Revenue (Rs.)',
                    data: rawChartData.revenue,
                    borderColor: '#10b981',
                    backgroundColor: revenueGradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: isDarkMode ? '#1e293b' : '#fff',
                        titleColor: isDarkMode ? '#f1f5f9' : '#1e293b',
                        bodyColor: isDarkMode ? '#94a3b8' : '#64748b',
                        borderColor: isDarkMode ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rs. ' + new Intl.NumberFormat('en-IN').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 0 }
                    },
                    y: {
                        grid: { color: gridColor },
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value === 0) return '0';
                                return 'Rs. ' + new Intl.NumberFormat('en-IN', { notation: "compact" , compactDisplay: "short" }).format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection