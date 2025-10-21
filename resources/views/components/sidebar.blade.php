<style>
    .sidebar {
        width: 260px;
        background-color: var(--sidebar-bg);
        color: var(--text-light);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        position: fixed;
        height: 100vh;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        transform: translateX(0);
    }

    .sidebar.collapsed~.main-content {
        margin-left: 70px;
        width: calc(100% - 70px);
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar.mobile-hidden {
        transform: translateX(-100%);
    }

    .sidebar-header {
        padding: 1.5rem 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar.collapsed .sidebar-header {
        justify-content: center;
    }

    .logo {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--accent), #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .brand-text h2 {
        font-size: 1.25rem;
        font-weight: 700;
    }

    .brand-text p {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .sidebar.collapsed .brand-text {
        display: none;
    }

    .sidebar-nav {
        flex: 1;
        padding: 1rem 0;
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    /* Custom scrollbar for sidebar */
    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 3px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .nav-section {
        margin-bottom: 1.5rem;
    }

    .nav-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 1.25rem;
        margin-bottom: 0.5rem;
        opacity: 0.7;
    }

    .sidebar.collapsed .nav-title {
        display: none;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.75rem 1.25rem;
        color: var(--text-light);
        text-decoration: none;
        transition: var(--transition);
        border-left: 3px solid transparent;
    }

    .nav-item:hover {
        background-color: var(--sidebar-hover);
        border-left-color: var(--accent);
    }

    .nav-item.active {
        background-color: var(--sidebar-hover);
        border-left-color: var(--accent);
    }

    .nav-item i {
        font-size: 1.125rem;
        width: 24px;
        text-align: center;
    }

    .nav-text {
        font-size: 0.95rem;
        font-weight: 500;
    }

    .sidebar.collapsed .nav-text {
        display: none;
    }

    .sidebar-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar.collapsed .sidebar-footer {
        padding: 1rem 0.75rem;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 0.75rem;
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        border: none;
        border-radius: var(--radius);
        transition: var(--transition);
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
    }

    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    .sidebar.collapsed .logout-btn span {
        display: none;
    }

    .sidebar.collapsed .logout-btn {
        justify-content: center;
        padding: 0.75rem 0;
    }

    .toggle-sidebar {
        position: absolute;
        top: 1.5rem;
        right: -12px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--accent);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: var(--transition);
        z-index: 1001;
    }

    .toggle-sidebar:hover {
        background: var(--accent-hover);
    }

    .sidebar.collapsed .toggle-sidebar i {
        transform: rotate(180deg);
    }

    .mobile-menu-btn {
        display: none;
        position: fixed;
        top: 1rem;
        left: 1rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--accent);
        color: white;
        border: none;
        cursor: pointer;
        z-index: 999;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            width: 280px;
        }

        .sidebar.mobile-visible {
            transform: translateX(0);
        }

        .mobile-menu-btn {
            display: flex;
        }

        .sidebar.collapsed~.main-content {
            margin-left: 0;
            width: 100%;
        }

        .toggle-sidebar {
            display: none;
        }
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    .sidebar-overlay.active {
        display: block;
    }

    .svg-icon {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<button class="mobile-menu-btn" id="mobileMenuBtn">
    <i class="svg-icon"><x-icons name="hamburger" /></i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="svg-icon"><x-icons name="weight" /></i>
        </div>
        <div class="brand-text">
            <h2>Al Hammd</h2>
            <p>Vehicle Management</p>
        </div>
    </div>

    <button class="toggle-sidebar" id="toggleSidebar">
        <i class="svg-icon"><x-icons name="left" /></i>
    </button>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-title">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="svg-icon"><x-icons name="dashboard" /></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-title">Weighing</div>
            <a href="{{ route('first.weight') }}"
                class="nav-item {{ request()->routeIs('first.weight') ? 'active' : '' }}">
                <i class="svg-icon"><x-icons name="weight-meter" /></i>
                <span class="nav-text">First Weight</span>
            </a>
            <a href="{{ route('second.weight') }}"
                class="nav-item {{ request()->routeIs('second.weight') ? 'active' : '' }}">
                <i class="svg-icon"><x-icons name="balance-scale" /></i>
                <span class="nav-text">Second Weight</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-title">Records</div>
            <a href="{{ route('print.record') }}"
                class="nav-item {{ request()->routeIs('print.record') ? 'active' : '' }}">
                <i class="svg-icon"><x-icons name="print" /></i>
                <span class="nav-text">Print Record</span>
            </a>
            <a href="{{ route('records.page') }}"
                class="nav-item {{ request()->routeIs('records.page') ? 'active' : '' }}">
                <i class="svg-icon"><x-icons name="calender" /></i>
                <span class="nav-text">Show Records</span>
            </a>
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('edit.page') }}" class="nav-item {{ request()->routeIs('edit.page') ? 'active' : '' }}">
                    <i class="svg-icon"><x-icons name="edit" /></i>
                    <span class="nav-text">Edit Record</span>
                </a>
                <a href="{{ route('delete.page') }}"
                    class="nav-item {{ request()->routeIs('delete.page') ? 'active' : '' }}">
                    <i class="svg-icon"><x-icons name="delete" /></i>
                    <span class="nav-text">Delete Record</span>
                </a>
            @endif
        </div>
        <div class="nav-section">
            <div class="nav-title">Profile</div>
            <a href="{{ route('profile.page') }}"
                class="nav-item {{ request()->routeIs('profile.page') ? 'active' : '' }}">
                <i class="svg-icon"><x-icons name="profile" /></i>
                <span class="nav-text">Profile</span>
            </a>
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('add.new.user') }}"
                    class="nav-item {{ request()->routeIs('add.new.user') ? 'active' : '' }}">
                    <i class="svg-icon"><x-icons name="add-user" /></i>
                    <span class="nav-text">Add New User</span>
                </a>
                <a href="{{ route('all.users') }}"
                    class="nav-item {{ request()->routeIs('all.users') ? 'active' : '' }}">
                    <i class="svg-icon"><x-icons name="users" /></i>
                    <span class="nav-text">All Users</span>
                </a>
            @endif
        </div>
        @if (Auth::user()->role === 'admin')
            <div class="nav-section">
                <div class="nav-title">Backup</div>
                <a href="{{ route('backup') }}" class="nav-item {{ request()->routeIs('backup') ? 'active' : '' }}">
                    <i class="svg-icon"><x-icons name="save" /></i>
                    <span class="nav-text">Create Backup</span>
                </a>
            </div>
        @endif
    </nav>

    <div class="sidebar-footer">
        <button class="logout-btn" id="logoutBtn">
            <i class="svg-icon"><x-icons name="logout" /></i>
            <span>Logout</span>
        </button>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleSidebar = document.getElementById('toggleSidebar');
        const logoutBtn = document.getElementById('logoutBtn');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const SIDEBAR_STATE_KEY = 'sidebar-collapsed';
        const MOBILE_VISIBLE_KEY = 'sidebar-mobile-visible';

        // 🔹 Restore sidebar state from localStorage
        const isCollapsed = localStorage.getItem(SIDEBAR_STATE_KEY) === 'true';
        const isMobileVisible = localStorage.getItem(MOBILE_VISIBLE_KEY) === 'true';

        if (isCollapsed) sidebar.classList.add('collapsed');
        if (isMobileVisible && window.innerWidth <= 768) {
            sidebar.classList.add('mobile-visible');
            sidebarOverlay.classList.add('active');
        }

        // 🔹 Handle sidebar toggle (desktop)
        toggleSidebar.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            const collapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem(SIDEBAR_STATE_KEY, collapsed);
        });

        // 🔹 Mobile menu toggle
        mobileMenuBtn.addEventListener('click', function () {
            sidebar.classList.add('mobile-visible');
            sidebarOverlay.classList.add('active');
            localStorage.setItem(MOBILE_VISIBLE_KEY, 'true');
        });

        // 🔹 Overlay click to close sidebar (mobile)
        sidebarOverlay.addEventListener('click', function () {
            sidebar.classList.remove('mobile-visible');
            sidebarOverlay.classList.remove('active');
            localStorage.setItem(MOBILE_VISIBLE_KEY, 'false');
        });

        // 🔹 Logout redirect
        logoutBtn.addEventListener('click', function () {
            window.location.href = '{{ route('logout') }}';
        });

        // 🔹 Handle window resize: reset mobile sidebar if going to desktop
        function handleResize() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-visible');
                sidebarOverlay.classList.remove('active');
                localStorage.setItem(MOBILE_VISIBLE_KEY, 'false');
            }
        }

        window.addEventListener('resize', handleResize);
    });
</script>