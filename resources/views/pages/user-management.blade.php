@extends('layouts.app')

@section('title', config('app.name') . ' - User Management')

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

    .table-title i { color: var(--accent); }

    .table-controls { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }

    .search-input {
      display: flex; align-items: center; gap: 0.5rem;
      background: var(--bg-primary); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 0.5rem 0.75rem;
    }
    .search-input input {
      border: none; outline: none; background: transparent; color: var(--text-primary); width: 220px;
    }

    select {
      padding: 0.5rem 1rem;
      background-color: var(--bg-secondary);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-primary);
      font-size: 0.9rem;
    }

    .table-wrapper { overflow-x: auto; border-radius: var(--radius); border: 1px solid var(--border); }
    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-track { background: var(--header-border); border-radius: 3px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: var(--header-border); border-radius: 3px; }
    .table-wrapper::-webkit-scrollbar-thumb:hover { background: var(--header-border); }

    table { width: 100%; border-collapse: collapse; min-width: 1000px; }
    thead { background: var(--bg-primary); }
    th {
      padding: 1rem; text-align: left; font-weight: 600; color: var(--text-primary);
      border-bottom: 1px solid var(--border); font-size: 0.9rem; white-space: nowrap;
    }
    td {
      padding: 1rem; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.875rem;
    }
    tbody tr { transition: var(--transition); }
    tbody tr:hover { background: var(--bg-primary); }
    tbody tr:last-child td { border-bottom: none; }

    .serial-column { font-weight: 600; color: var(--text-primary); text-align: center; width: 60px; }
    .date-column { white-space: nowrap; font-size: 0.85rem; }

    .badge {
      display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; border-radius: 999px; font-size: 0.75rem;
      border: 1px solid var(--border); background: var(--bg-primary); color: var(--text-secondary);
    }

    .user-column { display: flex; align-items: center; gap: 0.5rem; }
    .user-avatar-small {
      width: 28px; height: 28px; border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), #8b5cf6);
      display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;
    }

    .actions { display: flex; gap: 0.5rem; }
    .btn {
      padding: 0.5rem 0.75rem; border-radius: var(--radius); border: 1px solid var(--border);
      background: var(--bg-primary); color: var(--text-primary); cursor: pointer; transition: var(--transition);
      font-size: 0.875rem;
    }
    .btn:hover { background: var(--accent); color: white; border-color: var(--accent); }
    .btn-danger { color: var(--error); }
    .btn-danger:hover { background: var(--error); border-color: var(--error); color: white; }

    /* Status toggle */
    .status-toggle {
      position: relative; width: 44px; height: 24px; border-radius: 999px;
      background: var(--header-border); cursor: pointer; transition: var(--transition); border: 1px solid var(--border);
    }
    .status-toggle.active { background: var(--success); border-color: var(--success); }
    .status-knob {
      position: absolute; top: 1px; left: 2px; width: 20px; height: 20px; border-radius: 50%;
      background: white; transition: transform 0.2s ease-in-out; box-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .status-toggle.active .status-knob { transform: translateX(20px); }

    .table-footer {
      display: flex; justify-content: space-between; align-items: center;
      margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);
    }
    .pagination { display: flex; gap: 0.5rem; align-items: center; }
    .pagination-btn {
      padding: 0.5rem 0.75rem; background: var(--bg-primary); border: 1px solid var(--border);
      border-radius: var(--radius); color: var(--text-primary); cursor: pointer; transition: var(--transition);
      font-size: 0.875rem;
    }
    .pagination-btn:hover { background: var(--accent); color: white; border-color: var(--accent); }
    .pagination-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
    .pagination-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .records-info { color: var(--text-secondary); font-size: 0.875rem; }

    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center; padding: 1rem; z-index: 50; }
    .modal { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--radius); width: 100%; max-width: 520px; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); }
    .modal-title { font-weight: 700; color: var(--text-primary); }
    .modal-body { padding: 1rem 1.25rem; }
    .modal-footer { display: flex; gap: 0.75rem; justify-content: flex-end; padding: 1rem 1.25rem; border-top: 1px solid var(--border); }
    .input {
      width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--border); border-radius: var(--radius);
      background: var(--bg-primary); color: var(--text-primary);
    }
    .label { display: block; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; }
    .error-text { color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; }

    @media (max-width: 768px) {
      .table-container { padding: 1.5rem; }
      .table-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
      .table-controls { width: 100%; justify-content: space-between; }
    }
    @media (max-width: 480px) {
      .table-container { padding: 1.25rem; }
      .table-footer { flex-direction: column; gap: 1rem; align-items: flex-start; }
    }
  </style>
@endsection

@section('main-content')
  <x-header heading="User Management" para="View, update, and control user access" />

  <div class="table-container">
    <div class="table-header">
      <div class="table-title">
        <i class="svg-icon"><x-icons name="users"/></i>
        Users
      </div>

      <div class="table-controls">
        <div class="search-input">
          <i class="svg-icon" aria-hidden="true"><x-icons name="search"/></i>
          <input id="searchInput" type="text" placeholder="Search name or username" />
        </div>

        <div>
          <label for="roleFilter" class="label" style="margin:0;">Role</label>
          <select id="roleFilter">
            <option value="">All</option>
            <option value="admin">admin</option>
            <option value="operator">operator</option>
          </select>
        </div>

        <div>
          <label for="statusFilter" class="label" style="margin:0;">Status</label>
          <select id="statusFilter">
            <option value="">All</option>
            <option value="active">active</option>
            <option value="inactive">inactive</option>
          </select>
        </div>

        <button id="applyFilter" class="pagination-btn">Apply</button>
      </div>
    </div>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th class="serial-column">#</th>
            <th>User</th>
            <th>Username</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Status</th>
            <th>Joined</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="usersTable"></tbody>
      </table>
    </div>

    <div class="table-footer">
      <div class="records-info">
        Showing <span id="startRecord">0</span> to <span id="endRecord">0</span> of <span id="totalRecords">0</span> users
      </div>
      <div class="pagination" id="paginationContainer"></div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="modal-overlay" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal" role="document">
      <div class="modal-header">
        <span class="modal-title">Edit User</span>
        <button class="btn" id="editModalClose"><i class="svg-icon"><x-icons name="close"/></i></button>
      </div>
      <div class="modal-body">
        <form id="editForm" autocomplete="off">
          <input type="hidden" id="editUserId">
          <div style="display:grid; gap:0.75rem;">
            <div>
              <label class="label" for="editName">Name</label>
              <input class="input" type="text" id="editName" required>
              <div class="error-text" id="errorName"></div>
            </div>
            <div>
              <label class="label" for="editUsername">Username</label>
              <input class="input" type="text" id="editUsername" required>
              <div class="error-text" id="errorUsername"></div>
            </div>
            <div>
              <label class="label" for="editPhone">Phone</label>
              <input class="input" type="text" id="editPhone" required>
              <div class="error-text" id="errorPhone"></div>
            </div>
            <div>
              <label class="label" for="editRole">Role</label>
              <select class="input" id="editRole" required>
                <option value="admin">admin</option>
                <option value="operator">operator</option>
              </select>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem;">
              <span class="label" style="margin:0;">Status</span>
              <div id="editStatusToggle" class="status-toggle" role="switch" aria-checked="false" tabindex="0">
                <div class="status-knob"></div>
              </div>
              <span id="editStatusText" class="badge">inactive</span>
            </div>
            <div>
              <label class="label" for="editPassword">New Password <span class="badge">optional</span></label>
              <input class="input" type="password" id="editPassword" placeholder="Leave blank to keep current password">
              <div class="error-text" id="errorPassword"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn" id="editModalCancel">Cancel</button>
        <button class="btn" id="editModalSave">Save changes</button>
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div id="deleteModal" class="modal-overlay" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal" role="document">
      <div class="modal-header">
        <span class="modal-title">Delete User</span>
        <button class="btn" id="deleteModalClose"><i class="svg-icon"><x-icons name="close"/></i></button>
      </div>
      <div class="modal-body">
        <p style="color:var(--text-primary);">Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
        <p class="badge">This action cannot be undone</p>
      </div>
      <div class="modal-footer">
        <button class="btn" id="deleteModalCancel">Cancel</button>
        <button class="btn btn-danger" id="deleteModalConfirm">Delete</button>
      </div>
    </div>
  </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const usersTable       = document.getElementById('usersTable');
  const paginationEl     = document.getElementById('paginationContainer');
  const startRecordEl    = document.getElementById('startRecord');
  const endRecordEl      = document.getElementById('endRecord');
  const totalRecordsEl   = document.getElementById('totalRecords');
  const searchInput      = document.getElementById('searchInput');
  const roleFilter       = document.getElementById('roleFilter');
  const statusFilter     = document.getElementById('statusFilter');
  const applyBtn         = document.getElementById('applyFilter');
  const csrf             = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // Edit modal elements
  const editModal        = document.getElementById('editModal');
  const editUserId       = document.getElementById('editUserId');
  const editName         = document.getElementById('editName');
  const editUsername     = document.getElementById('editUsername');
  const editPhone        = document.getElementById('editPhone');
  const editRole         = document.getElementById('editRole');
  const editPassword     = document.getElementById('editPassword');
  const editStatusToggle = document.getElementById('editStatusToggle');
  const editStatusText   = document.getElementById('editStatusText');
  const editModalClose   = document.getElementById('editModalClose');
  const editModalCancel  = document.getElementById('editModalCancel');
  const editModalSave    = document.getElementById('editModalSave');

  // Delete modal elements
  const deleteModal        = document.getElementById('deleteModal');
  const deleteModalClose   = document.getElementById('deleteModalClose');
  const deleteModalCancel  = document.getElementById('deleteModalCancel');
  const deleteModalConfirm = document.getElementById('deleteModalConfirm');
  const deleteUserName     = document.getElementById('deleteUserName');
  let deleteUserId         = null;

  let current = {
    page: 1, perPage: 10, search: '', role: '', status: ''
  };

  function openModal(el) { el.style.display = 'flex'; el.setAttribute('aria-hidden', 'false'); }
  function closeModal(el) { el.style.display = 'none'; el.setAttribute('aria-hidden', 'true'); }

  function formatDate(dateString) {
    const d = new Date(dateString);
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
  }
  function formatNumber(n) {
    const num = Number(n || 0);
    return num.toLocaleString('en-US', { maximumFractionDigits: 0 });
  }

  function buildPagination(meta) {
    paginationEl.innerHTML = '';
    if (!meta || meta.last_page <= 1) return;

    const addBtn = (label, page, disabled = false, active = false) => {
      const btn = document.createElement('button');
      btn.className = 'pagination-btn' + (active ? ' active' : '');
      btn.textContent = label;
      btn.disabled = disabled;
      btn.addEventListener('click', () => {
        if (page && page !== current.page) { current.page = page; fetchAndRender(); }
      });
      paginationEl.appendChild(btn);
    };

    addBtn('<', meta.current_page - 1, meta.current_page === 1);
    const windowSize = 2;
    const start = Math.max(1, meta.current_page - windowSize);
    const end   = Math.min(meta.last_page, meta.current_page + windowSize);
    for (let p = start; p <= end; p++) addBtn(String(p), p, false, p === meta.current_page);
    addBtn('>', meta.current_page + 1, meta.current_page === meta.last_page);
  }

  function renderUsers(users, meta) {
    usersTable.innerHTML = '';
    if (!users || users.length === 0) {
      const row = document.createElement('tr');
      row.innerHTML = `<td colspan="8" style="text-align:center; padding:12px; color:#999;">No users found</td>`;
      usersTable.appendChild(row);
      return;
    }

    users.forEach((u, idx) => {
      const active = u.status === 'active';
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="serial-column">${(meta.from ?? 0) + idx}</td>
        <td>
          <div class="user-column">
            <div class="user-avatar-small">${(u.name || '?').toString().charAt(0).toUpperCase()}</div>
            <div>
              <div style="color:var(--text-primary); font-weight:600;">${u.name ?? ''}</div>
              <div class="badge" title="ID">#${u.id}</div>
            </div>
          </div>
        </td>
        <td>${u.username ?? ''}</td>
        <td>${u.phone ?? ''}</td>
        <td><span class="badge">${u.role ?? 'operator'}</span></td>
        <td>
          <div class="status-toggle ${active ? 'active' : ''}" data-id="${u.id}" role="switch" aria-checked="${active}" tabindex="0" title="${active ? 'active' : 'inactive'}">
            <div class="status-knob"></div>
          </div>
        </td>
        <td class="date-column">${u.created_at ? formatDate(u.created_at) : ''}</td>
        <td>
          <div class="actions">
            <button class="btn btn-edit" data-id="${u.id}"><i class="svg-icon"><x-icons name="edit"/></i></button>
            <button class="btn btn-danger btn-delete" data-id="${u.id}" data-name="${u.name ?? ''}"><i class="svg-icon"><x-icons name="delete"/></i></button>
          </div>
        </td>
      `;
      usersTable.appendChild(tr);
    });
  }

  function renderFooter(meta) {
    startRecordEl.textContent  = meta.from ? formatNumber(meta.from) : '0';
    endRecordEl.textContent    = meta.to ? formatNumber(meta.to) : '0';
    totalRecordsEl.textContent = meta.total ? formatNumber(meta.total) : '0';
  }

  async function fetchAndRender() {
    const params = new URLSearchParams({
      page: current.page, perPage: current.perPage,
      search: current.search || '', role: current.role || '', status: current.status || ''
    });

    const res = await fetch(`{{ route('users.fetch') }}?` + params.toString());
    if (!res.ok) { console.error('Failed to fetch users'); return; }
    const data = await res.json();

    renderUsers(data.users, data.pagination);
    renderFooter(data.pagination);
    buildPagination(data.pagination);
  }

  // Status toggle with optimistic UI
  async function toggleStatus(userId, el) {
    if (!csrf) return;
    const willBeActive = !el.classList.contains('active');
    el.classList.toggle('active');
    el.setAttribute('aria-checked', String(willBeActive));
    try {
      const res = await fetch(`{{ route('users.toggle', ':id') }}`.replace(':id', userId), {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({ status: willBeActive ? 'active' : 'inactive' })
      });
      if (!res.ok) throw new Error('Failed');
      const data = await res.json();
      showToast(data.message, 'success');
    } catch (e) {
      el.classList.toggle('active'); // revert
      el.setAttribute('aria-checked', String(!willBeActive));
      alert('Could not update status');
    }
  }

  // Edit modal helpers
  function setEditStatusUI(isActive) {
    if (isActive) {
      editStatusToggle.classList.add('active');
      editStatusToggle.setAttribute('aria-checked', 'true');
      editStatusText.textContent = 'active';
    } else {
      editStatusToggle.classList.remove('active');
      editStatusToggle.setAttribute('aria-checked', 'false');
      editStatusText.textContent = 'inactive';
    }
  }
  function bindEditStatusToggle() {
    const flip = () => setEditStatusUI(!editStatusToggle.classList.contains('active'));
    editStatusToggle.addEventListener('click', flip);
    editStatusToggle.addEventListener('keydown', e => { if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); flip(); } });
  }
  bindEditStatusToggle();

  async function submitEdit() {
    if (!csrf) return;
    const id = editUserId.value;
    const payload = {
      name: editName.value.trim(),
      username: editUsername.value.trim(),
      phone: editPhone.value.trim(),
      role: editRole.value,
      status: editStatusToggle.classList.contains('active') ? 'active' : 'inactive',
    };
    const pwd = editPassword.value;
    if (pwd && pwd.length > 0) payload.password = pwd;

    // clear errors
    document.getElementById('errorName').textContent = '';
    document.getElementById('errorUsername').textContent = '';
    document.getElementById('errorPhone').textContent = '';
    document.getElementById('errorPassword').textContent = '';

    try {
      const res = await fetch(`{{ route('users.update', ':id') }}`.replace(':id', id), {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify(payload)
      });

      if (res.status === 422) {
        const data = await res.json();
        document.getElementById('errorName').textContent = data.errors?.name?.[0] || '';
        document.getElementById('errorUsername').textContent = data.errors?.username?.[0] || '';
        document.getElementById('errorPhone').textContent = data.errors?.phone?.[0] || '';
        document.getElementById('errorPassword').textContent = data.errors?.password?.[0] || '';
        return;
      }
      if (!res.ok) throw new Error('Failed');
      closeModal(editModal);
      editPassword.value = '';
      const data = await res.json();
      showToast(data.message, 'success');
      await fetchAndRender();
    } catch (e) {
      alert('Could not save changes');
    }
  }

  async function submitDelete() {
    if (!csrf || !deleteUserId) return;
    try {
      const res = await fetch(`{{ route('users.destroy', ':id') }}`.replace(':id', deleteUserId), {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
      });
      if (!res.ok) throw new Error('Failed');
      closeModal(deleteModal);
      deleteUserId = null;
      const data = await res.json();
      showToast(data.message, 'success');
      await fetchAndRender();
    } catch (e) {
      alert('Could not delete user');
    }
  }

  // Table events
  usersTable.addEventListener('click', function (e) {
    const toggle = e.target.closest('.status-toggle');
    if (toggle) { toggleStatus(toggle.getAttribute('data-id'), toggle); return; }

    const editBtn = e.target.closest('.btn-edit');
    if (editBtn) {
      const row = editBtn.closest('tr');
      const id   = editBtn.getAttribute('data-id');
      const name = row.querySelector('.user-column div div:first-child')?.textContent?.trim() || '';
      const username = row.children[2]?.textContent?.trim() || '';
      const phone = row.children[3]?.textContent?.trim() || '';
      const role = row.children[4]?.innerText?.trim() || 'operator';
      const isActive = row.querySelector('.status-toggle')?.classList.contains('active') || false;

      editUserId.value = id;
      editName.value = name;
      editUsername.value = username;
      editPhone.value = phone;
      editRole.value = role.toLowerCase();
      setEditStatusUI(isActive);
      openModal(editModal);
      return;
    }

    const deleteBtn = e.target.closest('.btn-delete');
    if (deleteBtn) {
      deleteUserId = deleteBtn.getAttribute('data-id');
      deleteUserName.textContent = deleteBtn.getAttribute('data-name') || 'this user';
      openModal(deleteModal);
    }
  });

  // Modal controls
  [editModalClose, editModalCancel].forEach(btn => btn.addEventListener('click', () => closeModal(editModal)));
  [deleteModalClose, deleteModalCancel].forEach(btn => btn.addEventListener('click', () => { deleteUserId = null; closeModal(deleteModal); }));
  editModalSave.addEventListener('click', submitEdit);
  deleteModalConfirm.addEventListener('click', submitDelete);
  [editModal, deleteModal].forEach(modal => modal.addEventListener('click', e => { if (e.target === modal) closeModal(modal); }));

  // Filters
  let searchTimeout = null;
  searchInput.addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      current.search = searchInput.value.trim();
      current.page = 1;
      fetchAndRender();
    }, 350);
  });
  roleFilter.addEventListener('change', () => { current.role = roleFilter.value; });
  statusFilter.addEventListener('change', () => { current.status = statusFilter.value; });
  applyBtn.addEventListener('click', () => { current.page = 1; fetchAndRender(); });

  // Initial load
  fetchAndRender();
});
</script>
@endsection
