@extends('layouts.app')

@section('title', config('app.name') . ' - Add New User')

@section('css')
  {{-- Reuse your existing styles exactly as provided --}}
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
    .form-title i { color: var(--accent); }
    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1.5rem;
    }
    .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
    .form-group.full-width { grid-column: span 2; }
    label { font-weight: 500; color: var(--text-primary); font-size: 0.9rem; }
    .required::after { content: " *"; color: var(--error); }
    input, select {
      padding: 0.75rem 1rem;
      background-color: var(--input-bg, var(--bg-secondary));
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-primary);
      font-size: 1rem;
      transition: var(--transition);
    }
    input:focus, select:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    input::placeholder { color: var(--text-secondary); opacity: 0.7; }
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
    .btn-primary { background: var(--accent); color: white; }
    .btn-primary:hover { background: var(--accent-hover); }
    .btn-secondary {
      background: var(--bg-primary);
      color: var(--text-primary);
      border: 1px solid var(--border);
    }
    .btn-secondary:hover { background: var(--border); }

    /* Mobile Responsiveness */
    @media (max-width: 1024px) {
      .form-grid { grid-template-columns: 1fr; }
      .form-group.full-width { grid-column: span 1; }
    }
    @media (max-width: 768px) {
      .main-content { margin-left: 0; width: 100%; padding: 1rem; padding-top: 5rem; }
      .form-card { padding: 1.5rem; }
      .form-title { font-size: 1.25rem; }
      .form-actions { flex-direction: column; }
    }
    @media (max-width: 480px) { .user-info { display: none; } }
  </style>
@endsection

@section('main-content')
  <x-header heading="Add New User" para="Create a user account for the system" />

  <div class="form-container">
    <div class="form-card">
      <h2 class="form-title">
        New User Details
      </h2>

      <form id="createUserForm" autocomplete="off">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label for="name" class="required">Full Name</label>
            <input type="text" id="name" name="name" placeholder="e.g., Ali Khan" required autofocus>
          </div>

          <div class="form-group">
            <label for="username" class="required">Username</label>
            <input type="text" id="username" name="username" placeholder="unique username" required>
          </div>

          <div class="form-group">
            <label for="password" class="required">Password</label>
            <input type="password" id="password" name="password" placeholder="minimum 8 characters" minlength="8" required>
          </div>

          <div class="form-group">
            <label for="phone" class="required">Phone</label>
            <input type="tel" id="phone" name="phone" placeholder="03XXXXXXXXX" pattern="^[0-9+\-\s()]{7,20}$" required>
          </div>

          <div class="form-group full-width">
            <label for="role" class="required">Role</label>
            <select id="role" name="role" required>
              <option value="" selected disabled>Select role</option>
              <option value="operator">Operator</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" id="cancelBtn">
            <i class="svg-icon"><x-icons name="close"/></i>
            Cancel
          </button>
          <button type="submit" class="btn btn-primary" id="submitUserBtn">
            <i class="svg-icon"><x-icons name="save"/></i>
            Save User
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('createUserForm');
      const cancelBtn = document.getElementById('cancelBtn');
      const submitBtn = document.getElementById('submitUserBtn');

      // Enter key navigation across inputs
      const focusable = Array.from(form.querySelectorAll('input:not([type="button"]):not([type="submit"]), select'));

      focusable.forEach((el, index) => {
        el.addEventListener('keydown', function (e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            if (index === focusable.length - 1) {
              form.dispatchEvent(new Event('submit'));
            } else {
              focusable[index + 1].focus();
            }
          }
        });
      });

      // Submit handler
      form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // quick client-side checks
        const name = document.getElementById('name').value.trim();
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const phone = document.getElementById('phone').value.trim();
        const role = document.getElementById('role').value;

        if (!name || !username || !password || !phone || !role) {
          showToast('Please fill in all required fields', 'error');
          return;
        }
        if (password.length < 8) {
          showToast('Password must be at least 8 characters', 'error');
          document.getElementById('password').focus();
          return;
        }

        submitBtn.disabled = true;
        submitBtn.classList.add('loading');

        const formData = new FormData(form);

        // Adjust the route to match your backend endpoint
        const res = await ajaxRequest("{{ route('save.new.user') }}", "POST", formData);

        if (res && res.success) {
          showToast(res.message || 'User created', 'success');
          if (res.redirect) {
            setTimeout(() => { location.href = res.redirect; }, 450);
          } else {
            form.reset();
            focusable[0].focus();
          }
        } else {
          showToast(res.message || 'User not created', 'error');
          submitBtn.disabled = false;
          submitBtn.classList.remove('loading');
        }
      });

      // Cancel handler
      cancelBtn.addEventListener('click', function () {
        if (confirm('Are you sure you want to cancel? All unsaved data will be lost.')) {
          form.reset();
          document.getElementById('name').focus();
        }
      });
    });
  </script>
@endsection
