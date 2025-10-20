@extends('layouts.app')

@section('title', config('app.name') . ' - Edit Record')

@section('css')
<style>
  .form-container{max-width:900px;margin:0 auto}
  .form-card{background:var(--bg-secondary);border-radius:var(--radius);padding:2rem;box-shadow:var(--shadow);border:1px solid var(--border);margin-bottom:1.5rem}
  .form-title{font-size:1.5rem;font-weight:600;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.75rem}
  .form-title i{color:var(--accent)}
  .form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem}
  .form-group{display:flex;flex-direction:column;gap:.5rem}
  .form-group.full-width{grid-column:span 2}
  label{font-weight:500;color:var(--text-primary);font-size:.9rem}
  .required::after{content:" *";color:var(--error)}
  input,select,textarea{padding:.75rem 1rem;background-color:var(--input-bg,var(--bg-secondary));border:1px solid var(--border);border-radius:var(--radius);color:var(--text-primary);font-size:1rem;transition:var(--transition)}
  textarea{min-height:110px;resize:vertical}
  input:focus,select:focus,textarea:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,.1)}
  input::placeholder,textarea::placeholder{color:var(--text-secondary);opacity:.7}
  input[readonly]{background-color:var(--bg-primary);color:var(--text-secondary);cursor:not-allowed;border-color:var(--border)}
  .input-with-button{display:flex;gap:.5rem}
  .input-with-button input{flex:1}
  .search-btn{padding:.75rem 1.5rem;background:var(--success);color:#fff;border:none;border-radius:var(--radius);cursor:pointer;transition:var(--transition);font-weight:500;white-space:nowrap;display:flex;align-items:center;gap:.5rem}
  .search-btn:hover{background:#0da271;transform:translateY(-1px)}
  .search-btn:disabled{opacity:.6;cursor:not-allowed;transform:none}
  .search-btn.loading{position:relative;color:transparent}
  .search-btn.loading::after{content:"";position:absolute;width:16px;height:16px;top:50%;left:50%;margin-left:-8px;margin-top:-8px;border:2px solid transparent;border-top-color:#fff;border-radius:50%;animation:spin 1s linear infinite}
  .form-actions{display:flex;gap:1rem;justify-content:flex-end;margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--border)}
  .btn{padding:.75rem 1.5rem;border:none;border-radius:var(--radius);font-weight:500;cursor:pointer;transition:var(--transition);display:flex;align-items:center;gap:.5rem}
  .btn-primary{background:var(--accent);color:#fff}
  .btn-primary:hover{background:var(--accent-hover);transform:translateY(-1px)}
  .btn-secondary{background:var(--bg-primary);color:var(--text-primary);border:1px solid var(--border)}
  .btn-secondary:hover{background:var(--border);transform:translateY(-1px)}
  .record-details{background:var(--bg-primary);border-radius:var(--radius);padding:2rem;margin:2rem 0;border:1px solid var(--border)}
  .record-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border)}
  .record-title{font-size:1.25rem;font-weight:600;color:var(--accent)}
  .record-status{background:var(--accent);color:#fff;padding:.5rem 1rem;border-radius:20px;font-size:.875rem;font-weight:500}
  .readonly-field{background-color:var(--bg-primary);padding:.75rem 1rem;border-radius:var(--radius);border:1px solid var(--border);color:var(--text-secondary)}
  .field-label{font-weight:500;color:var(--text-primary);margin-bottom:.25rem}
  .hidden{display:none}
  @media (max-width:1024px){.form-grid{grid-template-columns:1fr}.form-group.full-width{grid-column:span 1}}
  @media (max-width:768px){.form-card{padding:1.5rem}.form-title{font-size:1.25rem}.input-with-button{flex-direction:column}.form-actions{flex-direction:column}.record-header{flex-direction:column;align-items:flex-start;gap:1rem}}
  @media (max-width:480px){.form-card{padding:1.25rem}.record-details{padding:1.5rem}}
  @keyframes spin{to{transform:rotate(360deg)}}
</style>
@endsection

@section('main-content')
  <x-header heading="Edit Record" para="Search a record, review details, and update fields" />

  <div class="form-container">
    <!-- Search -->
    <div class="form-card">
      <h2 class="form-title">Find Record to Edit</h2>
      <div class="form-grid">
        <div class="form-group full-width">
          <label for="searchSerial" class="required">Record ID</label>
          <div class="input-with-button">
            <input type="number" id="searchSerial" name="searchSerial" placeholder="Enter record ID (e.g., 1, 2, 3)" min="1" required>
            <button type="button" class="search-btn" id="searchBtn">
              <i class="svg-icon"><x-icons name="search"/></i>
              Search
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Record Summary (shown after search) -->
    <div class="record-details hidden" id="recordSummary">
      <div class="record-header">
        <div class="record-title">Record Found: ID <span id="summarySerial">—</span></div>
        <div class="record-status" id="summaryStatus">Loaded</div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <div class="field-label">Serial #</div>
          <div class="readonly-field" id="summarySerialReadonly">—</div>
        </div>
        <div class="form-group">
          <div class="field-label">Vehicle #</div>
          <div class="readonly-field" id="summaryVehicle">—</div>
        </div>
        <div class="form-group">
          <div class="field-label">Party</div>
          <div class="readonly-field" id="summaryParty">—</div>
        </div>
        <div class="form-group">
          <div class="field-label">Amount (PKR)</div>
          <div class="readonly-field" id="summaryAmount">—</div>
        </div>
      </div>
    </div>

    <!-- Edit Form -->
    <div class="form-card hidden" id="editFormCard">
      <h2 class="form-title">Edit Record</h2>

      <form id="editRecordForm" autocomplete="off">
        <!-- Serial: read-only in UI, but sent as hidden input -->
        <div class="form-grid">
          <div class="form-group">
            <label>Serial #</label>
            <input type="text" id="serial_display" value="" readonly>
            <input type="hidden" id="serial" name="serial" value="">
          </div>

          <div class="form-group">
            <label for="vehicle_number" class="required">Vehicle Number</label>
            <input type="text" id="vehicle_number" name="vehicle_number" placeholder="ABC-123" maxlength="15" required>
          </div>

          <div class="form-group">
            <label for="party" class="required">Party Name</label>
            <input type="text" id="party" name="party" placeholder="Ali Traders" maxlength="15" required>
          </div>

          <div class="form-group">
            <label for="amount">Amount (PKR)</label>
            <input type="number" id="amount" name="amount" placeholder="0" min="0" step="1" required>
          </div>

          <div class="form-group">
            <label for="first_weight">First Weight (kg)</label>
            <input type="number" id="first_weight" name="first_weight" placeholder="0.00" min="0" step="1" required>
          </div>

          <div class="form-group">
            <label for="second_weight">Second Weight (kg)</label>
            <input type="number" id="second_weight" name="second_weight" placeholder="0.00" min="0" step="1" required>
          </div>

          <div class="form-group full-width">
            <label for="description">Description</label>
            <input id="description" name="description" placeholder="Notes or extra info" maxlength="25"/>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" id="cancelBtn">
            <i class="svg-icon"><x-icons name="close"/></i>
            Cancel
          </button>
          <button type="submit" class="btn btn-primary" id="saveBtn">
            <i class="svg-icon"><x-icons name="save"/></i>
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Elements
  const searchBtn = document.getElementById('searchBtn');
  const searchSerial = document.getElementById('searchSerial');

  const recordSummary = document.getElementById('recordSummary');
  const summarySerial = document.getElementById('summarySerial');
  const summarySerialReadonly = document.getElementById('summarySerialReadonly');
  const summaryVehicle = document.getElementById('summaryVehicle');
  const summaryParty = document.getElementById('summaryParty');
  const summaryAmount = document.getElementById('summaryAmount');
  const summaryStatus = document.getElementById('summaryStatus');

  const editFormCard = document.getElementById('editFormCard');
  const editRecordForm = document.getElementById('editRecordForm');

  const serialHidden = document.getElementById('serial');
  const serialDisplay = document.getElementById('serial_display');
  const vehicle_number = document.getElementById('vehicle_number');
  const party = document.getElementById('party');
  const amount = document.getElementById('amount');
  const first_weight = document.getElementById('first_weight');
  const second_weight = document.getElementById('second_weight');
  const description = document.getElementById('description');

  const cancelBtn = document.getElementById('cancelBtn');
  const saveBtn = document.getElementById('saveBtn');

  // Search handler
  searchBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    const serial = searchSerial.value.trim();
    if (!serial) {
      alert('Please enter a record ID');
      return;
    }

    searchBtn.classList.add('loading');
    searchBtn.disabled = true;

    const formData = new FormData();
    formData.append('serial', serial);

    try {
      const res = await ajaxRequest('{{ route('get.record') }}', 'POST', formData);

      if (res?.success && res?.record) {
        showToast(res.message || 'Record loaded', 'success');
        populateSummary(res.record);
        populateForm(res.record);
        recordSummary.classList.remove('hidden');
        editFormCard.classList.remove('hidden');
        vehicle_number.focus();
      } else {
        showToast(res?.message || 'Record not found', 'error');
        recordSummary.classList.add('hidden');
        editFormCard.classList.add('hidden');
      }
    } catch (err) {
      console.error(err);
      showToast('Failed to fetch record', 'error');
    } finally {
      searchBtn.classList.remove('loading');
      searchBtn.disabled = false;
    }
  });

  // Populate summary area
  function populateSummary(record) {
    summarySerial.textContent = record.id;
    summarySerialReadonly.textContent = record.id;
    summaryVehicle.textContent = record.vehicle_number ?? '—';
    summaryParty.textContent = record.party ?? '—';
    summaryAmount.textContent = record.amount ?? '—';
    summaryStatus.textContent = record.second_weight ? 'Second Weight Present' : 'First Weight Only';
  }

  // Populate form fields
  function populateForm(record) {
    serialHidden.value = record.id;            // sent to backend
    serialDisplay.value = record.id;           // read-only UI
    vehicle_number.value = record.vehicle_number ?? '';
    party.value = record.party ?? '';
    amount.value = record.amount ?? '';
    first_weight.value = cleanNumber(record.first_weight);
    second_weight.value = cleanNumber(record.second_weight);
    description.value = record.description ?? '';
  }

  // Normalize numbers like "15,420.50 Kg" to "15420.50"
  function cleanNumber(val) {
    if (val === null || val === undefined) return '';
    return String(val).replace(/[^\d.]/g, '');
  }

  // Submit handler
  editRecordForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Basic checks
    if (!vehicle_number.value.trim()) {
      showToast('Vehicle number is required', 'error'); vehicle_number.focus(); return;
    }
    if (!party.value.trim()) {
      showToast('Party name is required', 'error'); party.focus(); return;
    }

    saveBtn.classList.add('loading');
    saveBtn.disabled = true;

    // Build payload (serial is included via hidden input)
    const formData = new FormData(editRecordForm);

    try {
      const res = await ajaxRequest('{{ route('update.record') }}', 'POST', formData);

      if (res?.success) {
        showToast(res.message || 'Record updated', 'success');
        if (res.record) {
          populateSummary(res.record);
          populateForm(res.record);
        }
        if (res.redirect) {
          setTimeout(() => { location.href = res.redirect }, 450);
        } else {
            location.reload();
        }
      } else {
        showToast(res?.message || 'Update failed', 'error');
      }
    } catch (err) {
      console.error(err);
      showToast('Request failed', 'error');
    } finally {
      saveBtn.classList.remove('loading');
      saveBtn.disabled = false;
    }
  });

  // Cancel button
  cancelBtn.addEventListener('click', function () {
    if (confirm('Discard changes?')) {
      editRecordForm.reset();
      recordSummary.classList.add('hidden');
      editFormCard.classList.add('hidden');
      searchSerial.focus();
    }
  });

  // Enter-to-search on ID
  searchSerial.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      searchBtn.click();
    }
  });

  // Enter-to-next on edit form
  const inputsForNav = Array.from(
    editRecordForm.querySelectorAll('input:not([type="hidden"]):not([readonly]), textarea')
  );
  inputsForNav.forEach((input, idx) => {
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        if (idx === inputsForNav.length - 1) {
          editRecordForm.dispatchEvent(new Event('submit'));
        } else {
          inputsForNav[idx + 1].focus();
        }
      }
    });
  });

  searchSerial.focus();
});
</script>
@endsection
