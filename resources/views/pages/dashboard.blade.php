@extends('layouts.app')

@section('title', config('app.name') . ' - Dashboard')

@section('css')
  <style>
    /* Surface & motion tokens (override or extend your existing vars) */
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

    .icon-1 {
      background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    }

    .icon-2 {
      background: linear-gradient(135deg, #10b981, #06b6d4);
    }

    .icon-3 {
      background: linear-gradient(135deg, #f59e0b, #ef4444);
    }

    .icon-4 {
      background: linear-gradient(135deg, #8b5cf6, #ec4899);
    }

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

    /* Recent activity */
    .recent-activity {
      grid-column: span 4;
      padding: 1.25rem;
    }

    .section-title {
      font-size: 1.1rem;
      font-weight: 700;
      letter-spacing: -0.01em;
      margin: 0 0 1rem 0;
    }

    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 0.85rem;
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

    /* Focus states for keyboard users */
    .card:focus-within,
    .activity-item:focus-within {
      box-shadow: var(--ring), 0 8px 24px rgba(0, 0, 0, 0.06);
      outline: none;
    }

    /* Light/Glass tweak: respect theme */
    @media (prefers-color-scheme: light) {
      .card {
        background: var(--glass);
      }
    }

    @media (prefers-color-scheme: dark) {
      .card {
        background: var(--glass-dark);
      }
    }

    /* Motion safety */
    @media (prefers-reduced-motion: reduce) {

      .card,
      .activity-item {
        transition: none;
      }
    }

    /* Responsive */
    @media (max-width: 1280px) {
      .dashboard-content {
        grid-template-columns: repeat(3, 1fr);
      }

      .recent-activity {
        grid-column: span 3;
      }
    }

    @media (max-width: 1024px) {
      .dashboard-content {
        grid-template-columns: repeat(2, 1fr);
      }

      .recent-activity {
        grid-column: span 2;
      }
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        width: 100%;
        padding: 1rem;
        padding-top: 3rem !important;
      }

      .dashboard-content {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      .recent-activity {
        grid-column: span 1;
      }

      .stat-card {
        flex-direction: row;
      }
    }

    @media (max-width: 480px) {
      .card {
        padding: 1rem;
      }

      .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
      }

      .stat-info h3 {
        font-size: 1.25rem;
      }

      .activity-item {
        padding: 0.8rem;
      }
    }
  </style>
@endsection

@section('main-content')
  <x-header heading="Dashboard" para="Welcome back, {{ Auth::user()->username }}! Here's your overview." />
  <div class="dashboard-content">
    <div class="card stat-card">
      <div class="stat-icon icon-1">
        <i class="svg-icon"><x-icons name="weight" /></i>
      </div>
      <div class="stat-info">
        <h3>{{ $today_weight ?? '' }}</h3>
        <p>Today's Weighings</p>
      </div>
    </div>

    <div class="card stat-card">
      <div class="stat-icon icon-2">
        <i class="svg-icon"><x-icons name="calender"/></i>
      </div>
      <div class="stat-info">
        <h3>{{ $weekly_weight ?? '' }}</h3>
        <p>Weekly Weighings</p>
      </div>
    </div>

    <div class="card stat-card">
      <div class="stat-icon icon-3">
        <i class="svg-icon"><x-icons name="invoice"/></i>
      </div>
      <div class="stat-info">
        <h3>{{ $today_user ?? '' }}</h3>
        <p>Done By You (Today)</p>
      </div>
    </div>

    <div class="card stat-card">
      <div class="stat-icon icon-4">
        <i class="svg-icon"><x-icons name="chart-line"/></i>
      </div>
      <div class="stat-info">
        <h3>{{ $monthly_weight ?? '' }}</h3>
        <p>Total This Month</p>
      </div>
    </div>

    <div class="card recent-activity">
      <h2 class="section-title">Recent Activity</h2>
      <div class="activity-list">
        @if (!empty($recent))
          @foreach ($recent as $record)
            <div class="activity-item">
              <div class="activity-icon">
                <i class="svg-icon"><x-icons name="weight" /></i>
              </div>
              <div class="activity-details">
                <h4>Weight Recorded</h4>
                <p>Vehicle #{{ $record->vehicle_number }} - {{ $record->first_weight }} kg</p>
              </div>
              <div class="activity-time">{{ \Carbon\Carbon::parse($record->created_at)->format('h:i A') }}</div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
@endsection