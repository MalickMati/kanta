@extends('layouts.app')

@section('title', config('app.name') . '- Second Weight')

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

    input[readonly] {
      background-color: var(--bg-primary);
      color: var(--text-secondary);
      cursor: not-allowed;
      border-color: var(--border);
    }

    .input-with-button {
      display: flex;
      gap: 0.5rem;
    }

    .input-with-button input {
      flex: 1;
    }

    .search-btn,
    .get-weight-btn {
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

    .search-btn:hover,
    .get-weight-btn:hover {
      background: #0da271;
      transform: translateY(-1px);
    }

    .search-btn:disabled,
    .get-weight-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .search-btn.loading,
    .get-weight-btn.loading {
      position: relative;
      color: transparent;
    }

    .search-btn.loading::after,
    .get-weight-btn.loading::after {
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
      background: var(--accent);
      color: white;
    }

    .btn-primary:hover {
      background: var(--accent-hover);
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

    .weight-display {
      background: var(--bg-primary);
      border-radius: var(--radius);
      padding: 1.5rem;
      margin-top: 1rem;
      border-left: 4px solid var(--success);
    }

    .weight-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--success);
    }

    .weight-unit {
      font-size: 1rem;
      color: var(--text-secondary);
      margin-top: 0.25rem;
    }

    .net-weight {
      background: var(--bg-primary);
      border-radius: var(--radius);
      padding: 1.5rem;
      margin-top: 1rem;
      border-left: 4px solid var(--accent);
    }

    .net-weight-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--accent);
    }

    .net-weight-label {
      font-size: 1rem;
      color: var(--text-secondary);
      margin-top: 0.25rem;
    }

    .search-section {
      margin-bottom: 2rem;
    }

    .record-details {
      background: var(--bg-primary);
      border-radius: var(--radius);
      padding: 2rem;
      margin: 2rem 0;
      border: 1px solid var(--border);
    }

    .record-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
    }

    .record-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--accent);
    }

    .record-status {
      background: var(--success);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 500;
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

    /* Mobile Responsiveness */
    @media (max-width: 1024px) {
      .form-grid {
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

      .record-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }

    }

    @media (max-width: 480px) {
      .form-card {
        padding: 1.25rem;
      }

      .record-details {
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
  <x-header heading="Second Weight Entry" para="Record the final weight and calculate net weight" />

  <div class="form-container">
    <!-- Search Section -->
    <div class="form-card search-section">
      <h2 class="form-title">
        Find First Weight Record
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

    <!-- Second Weight Form (Initially Hidden) -->
    <div class="form-card hidden" id="secondWeightFormCard">
      <h2 class="form-title">
        Second Weight Entry
      </h2>

      <div class="record-details">
        <div class="record-header">
          <div class="record-title">Record Found: ID <span id="recordSerial">1</span></div>
          <div class="record-status">First Weight Recorded</div>
        </div>

        <div class="form-grid">
          <div class="form-group">
            <div class="field-label">Serial #</div>
            <div class="readonly-field" id="serial">1</div>
          </div>

          <div class="form-group">
            <div class="field-label">Vehicle Number</div>
            <div class="readonly-field" id="vehicleNumber">ABC-123</div>
          </div>

          <div class="form-group">
            <div class="field-label">Party Name</div>
            <div class="readonly-field" id="partyName">Ali Traders</div>
          </div>

          <div class="form-group">
            <div class="field-label">First Weight (kg)</div>
            <div class="readonly-field" id="firstWeight">15,420.50 Kg</div>
          </div>

          <div class="form-group">
            <div class="field-label">Amount (PKR)</div>
            <div class="readonly-field" id="amount">2,500 Rs</div>
          </div>

        </div>
      </div>

      <form id="secondWeightForm" autocomplete="off">
        <input type="hidden" name="serial" id="second_serial" readonly>
        <div class="form-grid">
          <div class="form-group">
            <label for="secondWeight" class="required">Second Weight (kg)</label>
            <div class="input-with-button">
              <input type="number" id="secondWeight" name="secondWeight" placeholder="0" min="0" step="0.01" required>
              <button type="button" class="get-weight-btn" id="getWeightBtn">
                <i class="svg-icon"><x-icons name="refresh" /></i>
                Get Weight
              </button>
            </div>
          </div>

          <div class="form-group">
            <label for="Description">Description</label>
            <input type="text" id="Description" name="Description" placeholder="Brief description (3-4 words)"
              maxlength="50">
          </div>
        </div>

        <div class="weight-display" id="weightDisplay" style="display: none;">
          <div class="weight-value" id="weightValue">0.00</div>
          <div class="weight-unit">Second Weight Recorded</div>
        </div>

        <div class="net-weight" id="netWeightDisplay" style="display: none;">
          <div class="net-weight-value" id="netWeightValue">0.00</div>
          <div class="net-weight-label">Net Weight (First - Second)</div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" id="cancelBtn">
            <i class="svg-icon"><x-icons name="close" /></i>
            Cancel
          </button>
          <button type="submit" class="btn btn-primary" id="sub_btn">
            <i class="svg-icon"><x-icons name="save" /></i>
            Save Second Weight
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {

      // Search elements
      const searchBtn = document.getElementById('searchBtn');
      const searchSerial = document.getElementById('searchSerial');

      // Form elements
      const secondWeightFormCard = document.getElementById('secondWeightFormCard');
      const getWeightBtn = document.getElementById('getWeightBtn');
      const secondWeightInput = document.getElementById('secondWeight');
      const weightDisplay = document.getElementById('weightDisplay');
      const weightValue = document.getElementById('weightValue');
      const netWeightDisplay = document.getElementById('netWeightDisplay');
      const netWeightValue = document.getElementById('netWeightValue');
      const secondWeightForm = document.getElementById('secondWeightForm');
      const cancelBtn = document.getElementById('cancelBtn');
      const second_serial = document.getElementById('second_serial');
      const Description = document.getElementById('Description');
      const sub_btn = document.getElementById('sub_btn');

      // Search for record
      searchBtn.addEventListener('click', async e => {
        e.preventDefault();
        const serial = searchSerial.value.trim();

        if (!serial) {
          alert('Please enter a record ID');
          return;
        }

        // Show loading state
        searchBtn.classList.add('loading');
        searchBtn.disabled = true;

        const formData = new FormData();
        formData.append('serial', serial);

        const res = await ajaxRequest('{{ route('get.record') }}', 'POST', formData);

        if (res.success) {
          showToast(res.message || 'Record Found', 'success');
          loadRecordData(res.record);
          secondWeightFormCard.classList.remove('hidden');
          secondWeightInput.focus();
        } else {
          showToast(res.message || 'Record Not Found', 'error');
        }

        searchBtn.classList.remove('loading');
        searchBtn.disabled = false;
      });

      // Load record data into form
      function loadRecordData(record) {
        document.getElementById('recordSerial').textContent = record.id;
        document.getElementById('serial').textContent = record.id;
        document.getElementById('vehicleNumber').textContent = record.vehicle_number;
        document.getElementById('partyName').textContent = record.party;
        document.getElementById('firstWeight').textContent = record.first_weight;
        document.getElementById('amount').textContent = record.amount;
        Description.value = record.description || '';
        second_serial.value = record.id;
        secondWeightInput.value = record.second_weight;

        // Focus on second weight input
        secondWeightInput.focus();
      }

      // Get weight from serial port simulation
      getWeightBtn.addEventListener('click', async e => {
        getWeightBtn.classList.add('loading');
        getWeightBtn.disabled = true;

        const res = await ajaxRequest("{{ route('get.weight') }}", "GET",);

        secondWeightInput.value = res.weight;

        getWeightBtn.classList.remove('loading');
        getWeightBtn.disabled = false;
      });

      secondWeightForm.addEventListener('submit', async e => {
        e.preventDefault();

        sub_btn.classList.add('loading');
        sub_btn.disabled = true;

        const formData = new FormData(secondWeightForm);
        const data = Object.fromEntries(formData);

        // Basic validation
        if (!data.secondWeight) {
          alert('Please enter the second weight');
          return;
        }

        const res = await ajaxRequest('{{ route('save.second.weight') }}', 'POST', formData);

        if (res.success) {
          showToast(res.message, "success");
          if (res.redirect) {
            setTimeout(() => {
              location.href = res.redirect;
            }, 450);
            secondWeightForm.reset();
          }
          let serial = second_serial.value;
          serial++;
          second_serial.value = serial;
        } else {
          showToast(res.message || 'Weight not saved', 'error');
          sub_btn.disabled = false;
          sub_btn.classList.remove('loading');
        }

        secondWeightFormCard.classList.add('hidden');
        searchSerial.value = '';
        searchSerial.focus();
      });

      // Cancel button
      cancelBtn.addEventListener('click', function () {
        if (confirm('Are you sure you want to cancel? All unsaved data will be lost.')) {
          secondWeightForm.reset();
          weightDisplay.style.display = 'none';
          netWeightDisplay.style.display = 'none';
          secondWeightFormCard.classList.add('hidden');
          searchSerial.focus();
        }
      });

      // Enter key for search
      searchSerial.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          searchBtn.click();
        }
      });

      // Enter key navigation for second weight form
      const secondWeightInputs = Array.from(secondWeightForm.querySelectorAll('input:not([readonly]):not([type="button"]):not([type="submit"])'));

      secondWeightInputs.forEach((input, index) => {
        input.addEventListener('keydown', function (e) {
          if (e.key === 'Enter') {
            e.preventDefault();

            if (index === secondWeightInputs.length - 1) {
              secondWeightForm.dispatchEvent(new Event('submit'));
            } else {
              // Otherwise, focus the next input field
              secondWeightInputs[index + 1].focus();
            }
          }
        });
      });

      searchSerial.focus();
    });
  </script>
@endsection