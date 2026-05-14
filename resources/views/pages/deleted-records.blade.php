@extends('layouts.app')

@section('title', config('app.name') . ' - Deleted Records')

@section('css')
  <style>
    .table-container {
      background: var(--bg-secondary);
      border-radius: var(--radius);
      padding: 2rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      overflow: hidden;
    }

    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
    }

    .table-title {
      font-size: 1.5rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .table-title i {
      color: var(--danger, #ef4444);
    }

    .table-wrapper {
      overflow-x: auto;
      border-radius: var(--radius);
      border: 1px solid var(--border);
    }

    .table-wrapper::-webkit-scrollbar {
      height: 6px;
    }

    .table-wrapper::-webkit-scrollbar-track {
      background: var(--header-border);
      border-radius: 3px;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
      background: var(--header-border);
      border-radius: 3px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 1000px;
    }

    thead {
      background: var(--bg-primary);
    }

    th {
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      color: var(--text-primary);
      border-bottom: 1px solid var(--border);
      font-size: 0.9rem;
      white-space: nowrap;
    }

    td {
      padding: 1rem;
      border-bottom: 1px solid var(--border);
      color: var(--text-secondary);
      font-size: 0.875rem;
    }

    tbody tr:hover {
      background: var(--bg-primary);
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    .serial-column {
      font-weight: 600;
      color: var(--text-primary);
      text-align: center;
      width: 60px;
    }

    .amount-column {
      font-weight: 600;
      color: var(--success);
      text-align: right;
    }

    .weight-column {
      text-align: right;
    }

    .date-column {
      white-space: nowrap;
      font-size: 0.8rem;
    }

    .description-column {
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .badge-danger {
      background-color: rgba(239, 68, 68, 0.1);
      color: #ef4444;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-block;
    }

  </style>
@endsection

@section('main-content')
  <x-header heading="Deleted Records" para="View a list of all deleted records in the system" />

  <div class="table-container">
    <div class="table-header">
      <div class="table-title">
        <i class="fas fa-trash-alt"></i>
        Deleted Records
      </div>
    </div>

    <!-- Records Table -->
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th class="serial-column">#</th>
            <th>Party Name</th>
            <th>Vehicle No.</th>
            <th class="weight-column">First Weight</th>
            <th class="weight-column">Second Weight</th>
            <th class="weight-column">Net Weight</th>
            <th>Description</th>
            <th class="amount-column">Amount</th>
            <th class="date-column">Deleted At</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $r)
            <tr>
              <td class="serial-column">{{ $r->id }}</td>
              <td>{{ $r->party }}</td>
              <td>{{ $r->vehicle_number }}</td>
              <td class="weight-column">{{ number_format((float)$r->first_weight) }} kg</td>
              <td class="weight-column">{{ number_format((float)$r->second_weight) }} kg</td>
              <td class="weight-column">{{ number_format((float)$r->net_weight) }} kg</td>
              <td class="description-column" title="{{ $r->description }}">{{ $r->description }}</td>
              <td class="amount-column">{{ number_format((float)$r->amount) }} Rs</td>
              <td class="date-column">{{ \Carbon\Carbon::parse($r->deleted_at)->format('d M Y, h:i A') }}</td>
              <td><span class="badge-danger">Deleted</span></td>
            </tr>
          @empty
            <tr>
              <td colspan="10" style="text-align:center; padding:12px; color:#999;">No deleted records found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
@endsection
