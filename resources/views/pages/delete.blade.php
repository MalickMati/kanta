@extends('layouts.app')

@section('title', config('app.name') . ' - Delete Invoice')

@section('css')
  <style>
    .form-container {
      max-width: 900px;
      margin: 0 auto;
    }

    .form-card {
      background: var(--bg-secondary);
      border-radius: var(--radius);
      padding: 2rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      margin-bottom: 1.5rem;
    }

    .form-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .form-title i {
      color: var(--accent);
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1.5rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-group.full-width {
      grid-column: span 2;
    }

    label {
      font-weight: 500;
      color: var(--text-primary);
      font-size: 0.9rem;
    }

    .required::after {
      content: " *";
      color: var(--error);
    }

    input,
    select {
      padding: 0.75rem 1rem;
      background-color: var(--input-bg, var(--bg-secondary));
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-primary);
      font-size: 1rem;
      transition: var(--transition);
    }

    input:focus,
    select:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    input::placeholder {
      color: var(--text-secondary);
      opacity: 0.7;
    }

    .input-with-button {
      display: flex;
      gap: 0.5rem;
    }

    .input-with-button input {
      flex: 1;
    }

    .search-btn,
    .print-btn {
      padding: 0.75rem 1.5rem;
      background: var(--success);
      color: white;
      border: none;
      border-radius: var(--radius);
      cursor: pointer;
      transition: var(--transition);
      font-weight: 500;
      white-space: nowrap;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .print-btn {
      background: var(--accent);
    }

    .print-btn:hover {
      background: var(--accent-hover);
    }

    .search-btn:hover,
    .print-btn:hover {
      transform: translateY(-1px);
    }

    .search-btn:disabled,
    .print-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .search-btn.loading,
    .print-btn.loading {
      position: relative;
      color: transparent;
    }

    .search-btn.loading::after,
    .print-btn.loading::after {
      content: "";
      position: absolute;
      width: 16px;
      height: 16px;
      top: 50%;
      left: 50%;
      margin-left: -8px;
      margin-top: -8px;
      border: 2px solid transparent;
      border-top-color: white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    .form-actions {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--border);
    }

    .btn {
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: var(--radius);
      font-weight: 500;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary {
      background: var(--error);
      color: white;
    }

    .btn-primary:hover {
      background: var(--error);
      transform: translateY(-1px);
    }

    .btn-secondary {
      background: var(--bg-primary);
      color: var(--text-primary);
      border: 1px solid var(--border);
    }

    .btn-secondary:hover {
      background: var(--border);
      transform: translateY(-1px);
    }

    .search-section {
      margin-bottom: 2rem;
    }

    .invoice-preview {
      background: var(--bg-primary);
      border-radius: var(--radius);
      padding: 2rem;
      margin: 2rem 0;
      border: 1px solid var(--border);
    }

    .invoice-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--border);
    }

    .invoice-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--accent);
    }

    .invoice-number {
      font-size: 1rem;
      color: var(--text-secondary);
    }

    .invoice-details {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .detail-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .detail-label {
      font-weight: 600;
      color: var(--text-primary);
      font-size: 0.9rem;
    }

    .detail-value {
      color: var(--text-secondary);
      font-size: 1rem;
      padding: 0.5rem 0;
    }

    .weight-summary {
      background: var(--bg-secondary);
      border-radius: var(--radius);
      padding: 1.5rem;
      margin: 1.5rem 0;
    }

    .weight-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--border);
    }

    .weight-row:last-child {
      border-bottom: none;
      font-weight: 700;
      color: var(--accent);
      font-size: 1.1rem;
    }

    .weight-label {
      font-weight: 500;
    }

    .weight-value {
      font-weight: 600;
    }

    .amount-section {
      text-align: right;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 2px solid var(--border);
    }

    .total-amount {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--success);
    }

    .hidden {
      display: none;
    }

    .readonly-field {
      background-color: var(--bg-primary);
      padding: 0.75rem 1rem;
      border-radius: var(--radius);
      border: 1px solid var(--border);
      color: var(--text-secondary);
    }

    .field-label {
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }

    .field-value {
      color: var(--text-secondary);
      font-size: 1rem;
    }

    @media print {

      .sidebar,
      .header-actions,
      .search-section,
      .form-actions,
      .mobile-menu-btn {
        display: none !important;
      }

      .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 0 !important;
      }

      .form-card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
      }

      .invoice-preview {
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
      }

      body {
        background: white !important;
        color: black !important;
      }
    }

    @media (max-width: 1024px) {

      .form-grid,
      .invoice-details {
        grid-template-columns: 1fr;
      }

      .form-group.full-width {
        grid-column: span 1;
      }
    }

    @media (max-width: 768px) {
      .form-card {
        padding: 1.5rem;
      }

      .form-title {
        font-size: 1.25rem;
      }

      .input-with-button {
        flex-direction: column;
      }

      .form-actions {
        flex-direction: column;
      }

      .invoice-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }
    }

    @media (max-width: 480px) {
      .form-card {
        padding: 1.25rem;
      }

      .invoice-preview {
        padding: 1.5rem;
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
  <x-header heading="Delete Invoice" para="Find and Delete transaction invoices" />

  <div class="form-container">
    <!-- Search Section -->
    <div class="form-card search-section">
      <h2 class="form-title">
        Find Record to Delete
      </h2>

      <div class="form-grid">
        <div class="form-group full-width">
          <label for="searchSerial" class="required">Record ID</label>
          <div class="input-with-button">
            <input type="number" id="searchSerial" name="searchSerial" placeholder="Enter record ID (e.g., 1, 2, 3)"
              min="1" required>
            <button type="button" class="search-btn" id="searchBtn">
              <i class="svg-icon"><x-icons name="search" /></i>
              Search Record
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="form-card hidden" id="invoicePreviewCard">
      <h2 class="form-title">
        <i class="fas fa-receipt"></i>
        Invoice Preview
      </h2>

      <div class="invoice-preview" id="invoicePreview">
        <div class="invoice-header">
          <div>
            <div class="invoice-title">WEIGHT MANAGEMENT INVOICE</div>
            <div class="invoice-number">Invoice #: <span id="invoiceNumber">WM-001</span></div>
          </div>
          <div>
            <div class="detail-value">Date: <span id="invoiceDate">01 Dec, 2023</span></div>
            <div class="detail-value">Time: <span id="invoiceTime">10:30 AM</span></div>
          </div>
        </div>

        <div class="invoice-details">
          <div class="detail-group">
            <div class="detail-label">Record ID</div>
            <div class="detail-value" id="previewSerial">1</div>
          </div>

          <div class="detail-group">
            <div class="detail-label">Vehicle Number</div>
            <div class="detail-value" id="previewVehicleNumber">ABC-123</div>
          </div>

          <div class="detail-group">
            <div class="detail-label">Party Name</div>
            <div class="detail-value" id="previewPartyName">Ali Traders</div>
          </div>

          <div class="detail-group">
            <div class="detail-label">Description</div>
            <div class="detail-value" id="previewDescription">Cement bags delivery</div>
          </div>
        </div>

        <div class="weight-summary">
          <div class="weight-row">
            <div class="weight-label">First Weight</div>
            <div class="weight-value" id="previewFirstWeight">15,420.50 kg</div>
          </div>

          <div class="weight-row">
            <div class="weight-label">Second Weight</div>
            <div class="weight-value" id="previewSecondWeight">8,350.25 kg</div>
          </div>

          <div class="weight-row">
            <div class="weight-label">Net Weight</div>
            <div class="weight-value" id="previewNetWeight">7,070.25 kg</div>
          </div>

          <div class="weight-row">
            <div class="weight-label">Mound (Net Weight / 40)</div>
            <div class="weight-value" id="previewMound">176.76 mounds</div>
          </div>
        </div>

        <div class="amount-section">
          <div class="total-amount" id="previewAmount">PKR 2,500.00</div>
          <div class="detail-value">Amount in Pakistani Rupees</div>
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-secondary" id="cancelBtn">
          <i class="svg-icon"><x-icons name="close" /></i>
          Cancel
        </button>
        <button type="button" class="btn btn-primary print-btn" id="printBtn">
          <i class="svg-icon"><x-icons name="delete" /></i>
          Delete Invoice
        </button>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const searchBtn = document.getElementById('searchBtn');
      const searchSerial = document.getElementById('searchSerial');

      const invoicePreviewCard = document.getElementById('invoicePreviewCard');
      const printBtn = document.getElementById('printBtn');
      const cancelBtn = document.getElementById('cancelBtn');

      searchBtn.addEventListener('click', async e => {
        const serial = searchSerial.value.trim();

        if (!serial) {
          alert('Please enter a record ID');
          return;
        }

        searchBtn.classList.add('loading');
        searchBtn.disabled = true;

        const fd = new FormData();

        fd.append('serial', serial);

        const res = await ajaxRequest("{{ route('get.record') }}", 'POST', fd);

        if (res.success) {
          showToast(res.message, 'success');
          loadInvoiceData(res.record);
          invoicePreviewCard.classList.remove('hidden');
        } else {
          showToast(res.message, 'error');
        }

        searchBtn.classList.remove('loading');
        searchBtn.disabled = false;
      });

      function loadInvoiceData(record) {
        function extractDate(datetime) {
          if (!datetime) return "-";
          const [date, time] = datetime.split(" ");
          return { date, time };
        }

        const first = extractDate(record.first_date);
        const second = extractDate(record.second_date);

        const netWeight = parseFloat(record.net_weight || 0);
        const mound = netWeight / 40; // Example conversion if 1 mound = 40kg (adjust as needed)

        // Update invoice preview elements
        document.getElementById('invoiceNumber').textContent = `WM-${String(record.id).padStart(3, '0')}`;
        document.getElementById('invoiceDate').textContent = first.date;
        document.getElementById('invoiceTime').textContent = first.time;
        document.getElementById('previewSerial').textContent = record.id;
        document.getElementById('previewVehicleNumber').textContent = record.vehicle_number;
        document.getElementById('previewPartyName').textContent = record.party;
        document.getElementById('previewDescription').textContent = record.description;
        document.getElementById('previewFirstWeight').textContent = `${parseFloat(record.first_weight).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} kg`;
        document.getElementById('previewSecondWeight').textContent = `${parseFloat(record.second_weight).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} kg`;
        document.getElementById('previewNetWeight').textContent = `${netWeight.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} kg`;
        document.getElementById('previewMound').textContent = `${mound.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} mounds`;
        document.getElementById('previewAmount').textContent = `PKR ${parseFloat(record.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
      }

      // Print invoice
      printBtn.addEventListener('click', async e => {
        e.preventDefault();
        printBtn.disabled = true;
        printBtn.classList.add('loading');

        const formData = new FormData();

        formData.append('serial', document.getElementById('previewSerial').textContent);

        const res = await ajaxRequest('{{ route('delete.post') }}', 'POST', formData);

        if (res.success) {
          showToast(res.message || 'Redirecting', 'success');
          printBtn.disabled = false;
          printBtn.classList.remove('loading');
          invoicePreviewCard.classList.add('hidden');
          searchSerial.value = '';
          searchSerial.focus();
        } else {
          showToast(res.message || 'Error', 'error');
          printBtn.disabled = false;
          printBtn.classList.remove('loading');
        }
      });

      // Cancel button
      cancelBtn.addEventListener('click', function () {
        invoicePreviewCard.classList.add('hidden');
        searchSerial.value = '';
        searchSerial.focus();
      });

      // Enter key for search
      searchSerial.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          searchBtn.click();
        }
      });

      // Focus on search input when page loads
      searchSerial.focus();
    });
  </script>
@endsection