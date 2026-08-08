@extends('layouts.app')

@section('title', config('app.name') . ' - Vehicle Rate List')

@section('css')
  <style>
    .rate-container {
      max-width: 900px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .card {
      background: var(--bg-secondary);
      border-radius: var(--radius);
      padding: 1.5rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
    }

    .card-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1.25rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .card-title i {
      color: var(--accent);
    }

    .form-grid {
      display: grid;
      grid-template-columns: 2fr 1.5fr 1fr;
      gap: 1rem;
      align-items: end;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
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

    input {
      padding: 0.75rem 1rem;
      background-color: var(--input-bg, var(--bg-secondary));
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-primary);
      font-size: 1rem;
      transition: var(--transition);
    }

    input:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn {
      padding: 0.75rem 1.25rem;
      border: none;
      border-radius: var(--radius);
      font-weight: 500;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-size: 0.95rem;
    }

    .btn-primary {
      background: var(--accent);
      color: white;
    }

    .btn-primary:hover {
      background: var(--accent-hover);
    }

    .btn-danger {
      background: #ef4444;
      color: white;
    }

    .btn-danger:hover {
      background: #dc2626;
    }

    .btn-secondary {
      background: var(--bg-primary);
      color: var(--text-primary);
      border: 1px solid var(--border);
    }

    .btn-secondary:hover {
      background: var(--border);
    }

    /* Table Styles */
    .table-wrapper {
      overflow-x: auto;
      border-radius: var(--radius);
      border: 1px solid var(--border);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background: var(--bg-primary);
    }

    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid var(--border);
      font-size: 0.95rem;
    }

    th {
      font-weight: 600;
      color: var(--text-primary);
    }

    td {
      color: var(--text-secondary);
    }

    tbody tr:hover {
      background: var(--bg-primary);
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    .actions-cell {
      display: flex;
      gap: 0.5rem;
    }

    .action-btn {
      padding: 0.4rem 0.75rem;
      font-size: 0.85rem;
    }

    /* Edit Modal */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 2000;
      align-items: center;
      justify-content: center;
    }

    .modal-overlay.active {
      display: flex;
    }

    .modal-content {
      background: var(--bg-secondary);
      border-radius: var(--radius);
      padding: 1.5rem;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      border: 1px solid var(--border);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid var(--border);
    }

    .modal-title {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .close-modal {
      background: none;
      border: none;
      font-size: 1.25rem;
      color: var(--text-secondary);
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection

@section('main-content')
  <x-header heading="Vehicle Rate List" para="Manage default rates and vehicle types for operator reference" />

  <div class="rate-container">
    <!-- Add New Rate Form -->
    <div class="card">
      <h2 class="card-title">
        <i class="svg-icon"><x-icons name="add-user" /></i>
        Add New Vehicle Rate
      </h2>

      <form id="addRateForm" autocomplete="off">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label for="vehicle_type" class="required">Vehicle Type</label>
            <input type="text" id="vehicle_type" name="vehicle_type" placeholder="e.g. Rickshaw, Truck, Tractor" required autofocus>
          </div>

          <div class="form-group">
            <label for="amount" class="required">Amount (PKR)</label>
            <input type="number" id="amount" name="amount" placeholder="e.g. 500" min="0" step="0.01" required>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary" id="saveRateBtn">
              <i class="svg-icon"><x-icons name="save"/></i>
              Add Rate
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Existing Rates Table -->
    <div class="card">
      <h2 class="card-title">
        <i class="svg-icon"><x-icons name="records" /></i>
        Configured Vehicle Rates
      </h2>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Vehicle Type</th>
              <th>Default Amount (PKR)</th>
              <th style="width: 150px;">Actions</th>
            </tr>
          </thead>
          <tbody id="ratesTableBody">
            @forelse($rates as $index => $rate)
              <tr id="rate-row-{{ $rate->id }}">
                <td>{{ $index + 1 }}</td>
                <td class="rate-type">{{ $rate->vehicle_type }}</td>
                <td class="rate-amount">{{ number_format($rate->amount, 2) }} RS</td>
                <td class="actions-cell">
                  <button class="btn btn-secondary action-btn edit-btn" 
                          data-id="{{ $rate->id }}" 
                          data-type="{{ $rate->vehicle_type }}" 
                          data-amount="{{ $rate->amount }}">
                    <i class="svg-icon"><x-icons name="edit" /></i>
                    Edit
                  </button>
                  <button class="btn btn-danger action-btn delete-btn" 
                          data-id="{{ $rate->id }}" 
                          data-type="{{ $rate->vehicle_type }}">
                    <i class="svg-icon"><x-icons name="delete" /></i>
                    Delete
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; padding: 2rem;">No vehicle rates added yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Edit Rate Modal -->
  <div class="modal-overlay" id="editModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Edit Vehicle Rate</h3>
        <button class="close-modal" id="closeEditModal">&times;</button>
      </div>
      <form id="editRateForm" autocomplete="off">
        @csrf
        <input type="hidden" id="edit_id">
        <div class="form-group" style="margin-bottom: 1rem;">
          <label for="edit_vehicle_type" class="required">Vehicle Type</label>
          <input type="text" id="edit_vehicle_type" name="vehicle_type" required>
        </div>
        <div class="form-group" style="margin-bottom: 1.5rem;">
          <label for="edit_amount" class="required">Amount (PKR)</label>
          <input type="number" id="edit_amount" name="amount" min="0" step="0.01" required>
        </div>
        <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
          <button type="button" class="btn btn-secondary" id="cancelEditModal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="updateRateBtn">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const addRateForm = document.getElementById('addRateForm');
      const editRateForm = document.getElementById('editRateForm');
      const editModal = document.getElementById('editModal');
      const closeEditModal = document.getElementById('closeEditModal');
      const cancelEditModal = document.getElementById('cancelEditModal');
      const ratesTableBody = document.getElementById('ratesTableBody');

      // Add Rate Form Handler
      addRateForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(addRateForm);

        const res = await ajaxRequest("{{ route('ratelist.store') }}", "POST", formData);

        if (res && res.success) {
          showToast(res.message, "success");
          addRateForm.reset();
          location.reload();
        } else {
          showToast(res ? res.message : "Failed to add rate", "error");
        }
      });

      // Edit Button Handler
      document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
          const id = editBtn.getAttribute('data-id');
          const type = editBtn.getAttribute('data-type');
          const amount = editBtn.getAttribute('data-amount');

          document.getElementById('edit_id').value = id;
          document.getElementById('edit_vehicle_type').value = type;
          document.getElementById('edit_amount').value = amount;

          editModal.classList.add('active');
        }

        // Delete Button Handler
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
          const id = deleteBtn.getAttribute('data-id');
          const type = deleteBtn.getAttribute('data-type');

          if (confirm(`Are you sure you want to delete rate for "${type}"?`)) {
            deleteRate(id);
          }
        }
      });

      // Close Modal
      closeEditModal.addEventListener('click', () => editModal.classList.remove('active'));
      cancelEditModal.addEventListener('click', () => editModal.classList.remove('active'));

      // Edit Form Submission Handler
      editRateForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = document.getElementById('edit_id').value;
        const formData = new FormData(editRateForm);
        formData.append('_method', 'PUT');

        const url = "{{ route('ratelist.update', ':id') }}".replace(':id', id);
        const res = await ajaxRequest(url, "POST", formData);

        if (res && res.success) {
          showToast(res.message, "success");
          editModal.classList.remove('active');
          location.reload();
        } else {
          showToast(res ? res.message : "Failed to update rate", "error");
        }
      });

      // Delete Rate function
      async function deleteRate(id) {
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        formData.append('_token', "{{ csrf_token() }}");

        const url = "{{ route('ratelist.destroy', ':id') }}".replace(':id', id);
        const res = await ajaxRequest(url, "POST", formData);

        if (res && res.success) {
          showToast(res.message, "success");
          const row = document.getElementById(`rate-row-${id}`);
          if (row) row.remove();
        } else {
          showToast(res ? res.message : "Failed to delete rate", "error");
        }
      }
    });
  </script>
@endsection
