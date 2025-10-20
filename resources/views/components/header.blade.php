<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid var(--header-border);
    }

    .header-left h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .header-left p {
        color: var(--text-secondary);
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .theme-toggle {
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 1.25rem;
        transition: var(--transition);
        padding: 8px;
        border-radius: 50%;
        background-color: var(--bg-secondary);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .theme-toggle:hover {
        color: var(--accent);
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        background: var(--bg-secondary);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .user-role {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-top: 1rem;
        }

        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
    }

    @media (max-width: 480px) {
        .header-left h1 {
            font-size: 1.5rem;
        }
    }
</style>

<header class="header">
    <div class="header-left">
        <h1>{{ $heading }}</h1>
        <p>{{ $para }}</p>
    </div>
    <div class="header-actions">
        <button class="theme-toggle" id="themeToggle">
            <i class="svg-icon"><x-icons name="moon"/></i>
        </button>
        <div class="user-profile">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Str::title(Auth::user()->name) }}</div>
                <div class="user-role">{{ Str::title(Auth::user()->role) }}</div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = themeToggle.querySelector('i');

        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            } else if (systemPrefersDark) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }

            updateThemeIcon();
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            if (currentTheme === 'dark') {
                themeIcon.innerHTML = `<x-icons name="moon"/>`;
            } else {
                themeIcon.innerHTML = `<x-icons name="sun"/>`;
            }
        }

        initTheme();

        themeToggle.addEventListener('click', toggleTheme);

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('theme')) {
                document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
                updateThemeIcon();
            }
        });
    });
</script>