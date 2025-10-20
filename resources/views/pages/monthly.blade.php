@extends('layouts.app')

@section('title', config('app.name') . ' - Monthly Records')

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
      color: var(--accent);
    }

    .table-controls {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .month-selector {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    select {
      padding: 0.5rem 1rem;
      background-color: var(--bg-secondary);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-primary);
      font-size: 0.9rem;
    }

    .stats-summary {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .stat-card {
      background: var(--bg-primary);
      border-radius: var(--radius);
      padding: 1rem;
      text-align: center;
      border-left: 4px solid var(--accent);
    }

    .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 0.25rem;
    }

    .stat-label {
      font-size: 0.875rem;
      color: var(--text-secondary);
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

    .table-wrapper::-webkit-scrollbar-thumb:hover {
      background: var(--header-border);
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

    tbody tr {
      transition: var(--transition);
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

    .user-column {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .user-avatar-small {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), #8b5cf6);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 0.7rem;
      font-weight: 600;
    }

    .table-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 1px solid var(--border);
    }

    .pagination {
      display: flex;
      gap: 0.5rem;
      align-items: center;
    }

    .pagination-btn {
      padding: 0.5rem 0.75rem;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-primary);
      cursor: pointer;
      transition: var(--transition);
      font-size: 0.875rem;
    }

    .pagination-btn:hover {
      background: var(--accent);
      color: white;
      border-color: var(--accent);
    }

    .pagination-btn.active {
      background: var(--accent);
      color: white;
      border-color: var(--accent);
    }

    .pagination-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .records-info {
      color: var(--text-secondary);
      font-size: 0.875rem;
    }

    /* Mobile Responsiveness */
    @media (max-width: 1024px) {
      .stats-summary {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .table-container {
        padding: 1.5rem;
      }

      .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }

      .table-controls {
        width: 100%;
        justify-content: space-between;
      }

      .stats-summary {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .table-container {
        padding: 1.25rem;
      }

      .table-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
@endsection

@section('main-content')
  <x-header heading="All Records" para="Filter and analyze records by day, ISO week, or month" />

  <div class="table-container">
    <div class="table-header">
      <div class="table-title">
        <i class="fas fa-table"></i>
        Records
      </div>

      <div class="table-controls">
        <div class="month-selector" style="gap:0.75rem">
          <label for="periodSelect">Period:</label>
          <select id="periodSelect">
            <option value="day">Day wise</option>
            <option value="week">Week wise</option>
            <option value="month" selected>Month wise</option>
          </select>

          <label for="valueSelect" id="valueLabel">Month:</label>
          <select id="valueSelect"></select>

          <button id="applyFilter" class="pagination-btn">Apply</button>
        </div>
      </div>
    </div>

    <!-- Statistics Summary -->
    <div class="stats-summary">
      <div class="stat-card">
        <div id="statTotalRecords" class="stat-value">0</div>
        <div class="stat-label">Total Records</div>
      </div>
      <div class="stat-card">
        <div id="statTotalFirst" class="stat-value">0</div>
        <div class="stat-label">First Weight (kg)</div>
      </div>
      <div class="stat-card">
        <div id="statTotalSecond" class="stat-value">0</div>
        <div class="stat-label">Second Weight (kg)</div>
      </div>
      <div class="stat-card">
        <div id="statTotalAmount" class="stat-value">0</div>
        <div class="stat-label">Total Amount (PKR)</div>
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
            <th class="date-column">First Date</th>
            <th class="weight-column">Second Weight</th>
            <th class="date-column">Second Date</th>
            <th class="weight-column">Net Weight</th>
            <th>Description</th>
            <th class="amount-column">Amount</th>
            <th>Committed By</th>
          </tr>
        </thead>
        <tbody id="recordsTable"></tbody>
      </table>
    </div>

    <!-- Table Footer -->
    <div class="table-footer">
      <div class="records-info">
        Showing <span id="startRecord">0</span> to <span id="endRecord">0</span> of <span id="totalRecords">0</span> records
      </div>
      <div class="pagination" id="paginationContainer"></div>
    </div>
  </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', async function () {
  const periodSelect     = document.getElementById('periodSelect');
  const valueSelect      = document.getElementById('valueSelect');
  const valueLabel       = document.getElementById('valueLabel');
  const applyBtn         = document.getElementById('applyFilter');
  const recordsTable     = document.getElementById('recordsTable');
  const paginationEl     = document.getElementById('paginationContainer');

  const statTotalRecords = document.getElementById('statTotalRecords');
  const statTotalFirst   = document.getElementById('statTotalFirst');
  const statTotalSecond  = document.getElementById('statTotalSecond');
  const statTotalAmount  = document.getElementById('statTotalAmount');

  const startRecordEl    = document.getElementById('startRecord');
  const endRecordEl      = document.getElementById('endRecord');
  const totalRecordsEl   = document.getElementById('totalRecords');

  let periodsCache = null;
  let current = {
    period: 'month',
    value: null,
    page: 1,
    perPage: 10,
  };

  function formatDate(dateString) {
    const d = new Date(dateString);
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
  }

  function formatNumber(n, decimals = 0) {
    const num = Number(n || 0);
    return num.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: 2 });
  }

  function setValueLabel() {
    if (current.period === 'day')  valueLabel.textContent = 'Day:';
    if (current.period === 'week') valueLabel.textContent = 'Week:';
    if (current.period === 'month') valueLabel.textContent = 'Month:';
  }

  function populateValueSelect() {
    valueSelect.innerHTML = '';
    setValueLabel();

    const list = current.period === 'day'  ? periodsCache.days
               : current.period === 'week' ? periodsCache.weeks
               : periodsCache.months;

    if (!list || list.length === 0) {
      const opt = document.createElement('option');
      opt.value = '';
      opt.textContent = 'No data';
      valueSelect.appendChild(opt);
      current.value = '';
      return;
    }

    list.forEach(item => {
      const opt = document.createElement('option');
      opt.value = item.value;
      opt.textContent = item.label + ' (' + item.count + ')';
      valueSelect.appendChild(opt);
    });

    // Default to first available item if nothing chosen yet
    if (!current.value) {
      current.value = list[0].value;
    }
    valueSelect.value = current.value;
  }

  async function loadPeriods() {
    const res = await fetch(`{{ route('records.periods') }}`);
    if (!res.ok) {
      console.error('Failed to load period options');
      return;
    }
    periodsCache = await res.json();
    populateValueSelect();
  }

  function buildPagination(meta) {
    paginationEl.innerHTML = '';

    function addBtn(label, page, disabled = false, active = false) {
      const btn = document.createElement('button');
      btn.className = 'pagination-btn' + (active ? ' active' : '');
      btn.textContent = label;
      btn.disabled = disabled;
      btn.addEventListener('click', () => {
        if (page && page !== current.page) {
          current.page = page;
          fetchAndRender();
        }
      });
      paginationEl.appendChild(btn);
    }

    if (meta.last_page <= 1) return;

    addBtn('<', meta.current_page - 1, meta.current_page === 1);

    // windowed pages
    const window = 2;
    const start = Math.max(1, meta.current_page - window);
    const end   = Math.min(meta.last_page, meta.current_page + window);

    for (let p = start; p <= end; p++) {
      addBtn(String(p), p, false, p === meta.current_page);
    }

    addBtn('>', meta.current_page + 1, meta.current_page === meta.last_page);
  }

  function renderTable(records) {
    recordsTable.innerHTML = '';
    if (!records || records.length === 0) {
      const row = document.createElement('tr');
      row.innerHTML = `<td colspan="11" style="text-align:center; padding:12px; color:#999;">No records found</td>`;
      recordsTable.appendChild(row);
      return;
    }

    records.forEach(r => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="serial-column">${r.id}</td>
        <td>${r.partyName ?? ''}</td>
        <td>${r.vehicleNumber ?? ''}</td>
        <td class="weight-column">${formatNumber(r.firstWeight)} kg</td>
        <td class="date-column">${r.firstDate ? formatDate(r.firstDate) : ''}</td>
        <td class="weight-column">${formatNumber(r.secondWeight)} kg</td>
        <td class="date-column">${r.secondDate ? formatDate(r.secondDate) : ''}</td>
        <td class="weight-column">${formatNumber(r.netWeight)} kg</td>
        <td class="description-column" title="${r.description ?? ''}">${r.description ?? ''}</td>
        <td class="amount-column">${formatNumber(r.amount)} Rs</td>
        <td>
          <div class="user-column">
            <div class="user-avatar-small">${(r.committedBy || '?').toString().charAt(0).toUpperCase()}</div>
            <span>${r.committedBy ?? ''}</span>
          </div>
        </td>
      `;
      recordsTable.appendChild(tr);
    });
  }

  function renderStats(stats) {
    statTotalRecords.textContent = formatNumber(stats.total_records);
    statTotalFirst.textContent   = formatNumber(stats.total_first);
    statTotalSecond.textContent  = formatNumber(stats.total_second);
    statTotalAmount.textContent  = formatNumber(stats.total_amount);
  }

  function renderFooter(meta) {
    startRecordEl.textContent  = meta.from ? formatNumber(meta.from) : '0';
    endRecordEl.textContent    = meta.to ? formatNumber(meta.to) : '0';
    totalRecordsEl.textContent = meta.total ? formatNumber(meta.total) : '0';
  }

  async function fetchAndRender() {
    if (!current.value) return;

    const params = new URLSearchParams({
      period: current.period,
      value: current.value,
      page: current.page,
      perPage: current.perPage
    });

    const res = await fetch(`{{ route('records.fetch') }}?` + params.toString());
    if (!res.ok) {
      console.error('Failed to fetch records');
      return;
    }
    const data = await res.json();

    renderTable(data.records);
    renderStats(data.stats);
    renderFooter(data.pagination);
    buildPagination(data.pagination);
  }

  // Events
  periodSelect.addEventListener('change', () => {
    current.period = periodSelect.value;
    current.value = null;
    current.page = 1;
    populateValueSelect();
  });

  valueSelect.addEventListener('change', () => {
    current.value = valueSelect.value;
    current.page = 1;
  });

  applyBtn.addEventListener('click', () => {
    current.page = 1;
    fetchAndRender();
  });

  // Bootstrap: load periods, default to latest month/week/day with data, then fetch
  await loadPeriods();
  // Default to month-wise latest available
  if (periodsCache && periodsCache.months && periodsCache.months.length) {
    current.period = 'month';
    periodSelect.value = 'month';
    current.value = periodsCache.months[0].value;
    valueSelect.value = current.value;
    await fetchAndRender();
  } else if (periodsCache && periodsCache.weeks && periodsCache.weeks.length) {
    current.period = 'week';
    periodSelect.value = 'week';
    current.value = periodsCache.weeks[0].value;
    valueSelect.value = current.value;
    await fetchAndRender();
  } else if (periodsCache && periodsCache.days && periodsCache.days.length) {
    current.period = 'day';
    periodSelect.value = 'day';
    current.value = periodsCache.days[0].value;
    valueSelect.value = current.value;
    await fetchAndRender();
  }
});
</script>
@endsection
