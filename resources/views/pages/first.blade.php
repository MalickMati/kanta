@extends('layouts.app')

@section('title', config('app.name') . ' - First Weight')

@section('css')
  <style>
    .form-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .form-card {
      background: var(--bg-secondary);
      border-radius: var(--radius);
      padding: 2rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
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
    }

    .input-with-button {
      display: flex;
      gap: 0.5rem;
    }

    .input-with-button input {
      flex: 1;
    }

    .get-weight-btn {
      padding: 0.75rem 1rem;
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

    .get-weight-btn:hover {
      background: #0da271;
    }

    .get-weight-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .get-weight-btn.loading {
      position: relative;
      color: transparent;
    }

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
    }

    .btn-secondary {
      background: var(--bg-primary);
      color: var(--text-primary);
      border: 1px solid var(--border);
    }

    .btn-secondary:hover {
      background: var(--border);
    }

    .weight-display {
      background: var(--bg-primary);
      border-radius: var(--radius);
      padding: 1rem;
      margin-top: 1rem;
      border-left: 4px solid var(--success);
    }

    .weight-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--success);
    }

    .weight-unit {
      font-size: 1rem;
      color: var(--text-secondary);
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
      .main-content {
        margin-left: 0;
        width: 100%;
        padding: 1rem;
        padding-top: 5rem;
      }

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

    }

    @media (max-width: 480px) {
      .user-info {
        display: none;
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
  <x-header heading="First Weight Entry" para="Record the initial weight of the vehicle" />

  <div class="form-container">
    <div class="form-card">
      <h2 class="form-title">
        Vehicle First Weight Details
      </h2>

      <form id="firstWeightForm" autocomplete="off">
        <div class="form-grid">
          <div class="form-group">
            <label for="serial">Serial Number</label>
            <input type="text" id="serial" name="serial" value="{{ $serial }}" readonly>
          </div>

          <div class="form-group">
            <label for="vehicleNumber" class="required">Vehicle Number</label>
            <input type="text" id="vehicleNumber" name="vehicleNumber" placeholder="e.g., ABC-123" required autofocus>
          </div>

          <div class="form-group">
            <label for="partyName" class="required">Party Name</label>
            <input type="text" id="partyName" name="partyName" maxlength="20" placeholder="Enter party name" required>
          </div>

          <div class="form-group">
            <label for="firstWeight" class="required">First Weight (kg)</label>
            <div class="input-with-button">
              <input type="number" id="firstWeight" name="firstWeight" placeholder="0" min="0" step="0.01" required>
              <button type="button" class="get-weight-btn" id="getWeightBtn" style="display:none">
                <i class="fas fa-sync-alt"></i>
                Get Weight
              </button>
            </div>
          </div>

          <div class="form-group">
            <label for="amount" class="required">Amount (PKR)</label>
            <input type="number" id="amount" name="amount" placeholder="0" min="0" step="0.01" required>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" placeholder="Brief description (3-4 words)"
              maxlength="25">
          </div>
        </div>

        <div class="weight-display" id="weightDisplay" style="display: none;">
          <div class="weight-value" id="weightValue">0.00</div>
          <div class="weight-unit">kilograms</div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" id="cancelBtn">
            <i class="svg-icon"><x-icons name="close"/></i>
            Cancel
          </button>
          <button type="submit" class="btn btn-primary" id="sub_btn">
            <i class="svg-icon"><x-icons name="save"/></i>
            Save First Weight
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const getWeightBtn = document.getElementById('getWeightBtn');
      const firstWeightInput = document.getElementById('firstWeight');
      const weightDisplay = document.getElementById('weightDisplay');
      const weightValue = document.getElementById('weightValue');
      const firstWeightForm = document.getElementById('firstWeightForm');
      const cancelBtn = document.getElementById('cancelBtn');
      const sub_btn = document.getElementById('sub_btn');

      // Get all form inputs for Enter key navigation
      const formInputs = Array.from(firstWeightForm.querySelectorAll('input:not([readonly]):not([type="button"]):not([type="submit"])'));

      getWeightBtn.addEventListener('click', function () {
        getWeightBtn.classList.add('loading');
        getWeightBtn.disabled = true;

        // Simulate reading from serial port
        setTimeout(() => {
          const randomWeight = (Math.random() * 20000 + 5000).toFixed(2);

          firstWeightInput.value = randomWeight;

          weightValue.textContent = randomWeight;
          weightDisplay.style.display = 'block';

          // Remove loading state
          getWeightBtn.classList.remove('loading');
          getWeightBtn.disabled = false;
        }, 1500);
      });

      formInputs.forEach((input, index) => {
        input.addEventListener('keydown', function (e) {
          if (e.key === 'Enter') {
            e.preventDefault();

            // If it's the last input field, submit the form
            if (index === formInputs.length - 1) {
              firstWeightForm.dispatchEvent(new Event('submit'));
            } else {
              // Otherwise, focus the next input field
              formInputs[index + 1].focus();
            }
          }
        });
      });

      // Form submission
      firstWeightForm.addEventListener('submit', async e => {
        e.preventDefault();

        sub_btn.disabled = true;
        sub_btn.classList.add('loading');

        const formData = new FormData(firstWeightForm);
        const data = Object.fromEntries(formData);

        if (!data.vehicleNumber || !data.partyName || !data.firstWeight || !data.amount) {
          alert('Please fill in all required fields');
          return;
        }

        const res = await ajaxRequest("{{ route('save.first.weight') }}", "POST", formData);

        if(res.success) {
          showToast(res.message, "success");
          if(res.redirect){
            setTimeout(() => {
              location.href = res.redirect;
            }, 450);
            firstWeightForm.reset();
          }
          let serial = document.getElementById('serial').value;
          serial++;
          document.getElementById('serial').value = serial;
        } else {
          showToast(res.message || 'Weight not saved', 'error');
          sub_btn.disabled = false;
          sub_btn.classList.remove('loading');
        }
      });

      // Cancel button
      cancelBtn.addEventListener('click', function () {
        if (confirm('Are you sure you want to cancel? All unsaved data will be lost.')) {
          firstWeightForm.reset();
          weightDisplay.style.display = 'none';
          document.getElementById('vehicleNumber').focus();
        }
      });
    });
  </script>

@endsection