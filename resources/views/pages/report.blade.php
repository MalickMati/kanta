@extends('layouts.app')

@section('title', config('app.name') . ' - Print Report')

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
      flex-direction: column;
      gap: 1rem;
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
      color: var(--accent);
    }

    .filter-controls {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      align-items: center;
      background: var(--bg-primary);
      padding: 1rem;
      border-radius: var(--radius);
      border: 1px solid var(--border);
    }

    .filter-group {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .radio-group {
      display: flex;
      gap: 1rem;
      font-weight: 500;
    }

    input[type="date"], input[type="number"] {
      padding: 0.5rem 1rem;
      background-color: var(--bg-secondary);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-primary);
      font-size: 0.9rem;
    }

    .btn {
      padding: 0.6rem 1.2rem;
      border-radius: var(--radius);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary {
      background: var(--accent);
      color: white;
    }

    .btn-primary:hover {
      background: var(--accent-hover, #6d28d9);
    }

    .btn-secondary {
      background: var(--bg-secondary);
      color: var(--text-primary);
      border: 1px solid var(--border);
    }

    .btn-secondary:hover {
      background: var(--bg-primary);
    }

    .table-wrapper {
      overflow-x: auto;
      border-radius: var(--radius);
      border: 1px solid var(--border);
      max-height: 500px;
      overflow-y: auto;
    }

    .table-wrapper::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    .table-wrapper::-webkit-scrollbar-track {
      background: var(--header-border);
      border-radius: 3px;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 3px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 800px;
    }

    thead {
      background: var(--bg-primary);
      position: sticky;
      top: 0;
      z-index: 10;
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

    .hidden {
      display: none !important;
    }

  </style>
@endsection

@section('main-content')
  <x-header heading="Print Report" para="Generate and print records report by date or serial range" />

  <div class="table-container">
    <div class="table-header">
      <div class="table-title">
        <i class="fas fa-print"></i>
        Report Generator
      </div>

      <div class="filter-controls">
        <div class="radio-group">
          <label>
            <input type="radio" name="filter_type" value="date" checked> Date Range
          </label>
          <label>
            <input type="radio" name="filter_type" value="serial"> Serial Range
          </label>
        </div>

        <!-- Date Range Inputs -->
        <div id="dateFilterGroup" class="filter-group">
          <label>From:</label>
          <input type="date" id="fromDate">
          <label>To:</label>
          <input type="date" id="toDate">
        </div>

        <!-- Serial Range Inputs -->
        <div id="serialFilterGroup" class="filter-group hidden">
          <label>From Serial:</label>
          <input type="number" id="fromSerial" placeholder="e.g. 100">
          <label>To Serial:</label>
          <input type="number" id="toSerial" placeholder="Leave blank for last">
        </div>

        <div class="filter-group" style="margin-left: auto;">
          <button id="btnPreview" class="btn btn-secondary">
            <i class="fas fa-eye"></i> Show Preview
          </button>
          <button id="btnPrint" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Details
          </button>
        </div>
      </div>
    </div>

    <!-- Records Table -->
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Serial #</th>
            <th>Party Name</th>
            <th style="text-align: right;">First Weight</th>
            <th style="text-align: right;">Second Weight</th>
            <th style="text-align: right;">Net Weight</th>
            <th style="text-align: right;">Amount</th>
          </tr>
        </thead>
        <tbody id="recordsTable">
          <tr>
            <td colspan="6" style="text-align: center; padding: 2rem;">Select criteria and click "Show Preview" to view records.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const filterTypeRadios = document.querySelectorAll('input[name="filter_type"]');
  const dateFilterGroup = document.getElementById('dateFilterGroup');
  const serialFilterGroup = document.getElementById('serialFilterGroup');
  
  const fromDate = document.getElementById('fromDate');
  const toDate = document.getElementById('toDate');
  const fromSerial = document.getElementById('fromSerial');
  const toSerial = document.getElementById('toSerial');
  
  const btnPreview = document.getElementById('btnPreview');
  const btnPrint = document.getElementById('btnPrint');
  const recordsTable = document.getElementById('recordsTable');

  // Set default dates
  const today = new Date().toISOString().split('T')[0];
  fromDate.value = today;
  toDate.value = today;

  // Toggle filter groups
  filterTypeRadios.forEach(radio => {
    radio.addEventListener('change', (e) => {
      if (e.target.value === 'date') {
        dateFilterGroup.classList.remove('hidden');
        serialFilterGroup.classList.add('hidden');
      } else {
        dateFilterGroup.classList.add('hidden');
        serialFilterGroup.classList.remove('hidden');
      }
    });
  });

  function getFilterParams() {
    const type = document.querySelector('input[name="filter_type"]:checked').value;
    const params = new URLSearchParams();
    params.append('filter_type', type);
    
    if (type === 'date') {
      if (fromDate.value) params.append('from_date', fromDate.value);
      if (toDate.value) params.append('to_date', toDate.value);
    } else {
      if (fromSerial.value) params.append('from_serial', fromSerial.value);
      if (toSerial.value) params.append('to_serial', toSerial.value);
    }
    
    return params;
  }

  function formatNumber(n, decimals = 0) {
    const num = Number(n || 0);
    return num.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: 2 });
  }

  btnPreview.addEventListener('click', async () => {
    const params = getFilterParams();
    recordsTable.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">Loading...</td></tr>';
    
    try {
      const res = await fetch(`{{ route('report.fetch') }}?` + params.toString());
      if (!res.ok) throw new Error('Failed to fetch records');
      
      const data = await res.json();
      
      if (!data.records || data.records.length === 0) {
        recordsTable.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No records found for the selected criteria.</td></tr>';
        return;
      }
      
      recordsTable.innerHTML = '';
      data.records.forEach(r => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${r.id}</td>
          <td>${r.party ?? '-'}</td>
          <td style="text-align: right;">${formatNumber(r.first_weight)} kg</td>
          <td style="text-align: right;">${formatNumber(r.second_weight)} kg</td>
          <td style="text-align: right;">${formatNumber(r.net_weight)} kg</td>
          <td style="text-align: right;">${formatNumber(r.amount)} Rs</td>
        `;
        recordsTable.appendChild(tr);
      });
      
    } catch (err) {
      console.error(err);
      recordsTable.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: red;">Error fetching records.</td></tr>';
    }
  });

  btnPrint.addEventListener('click', () => {
    const params = getFilterParams();
    const url = `{{ route('report.print') }}?` + params.toString();
    window.open(url, '_blank');
  });
});
</script>
@endsection
