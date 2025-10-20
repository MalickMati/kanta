<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vehicle Weight Management — Login</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/login.css?v=2') }}">
</head>

<body>
    <x-toast />
    <x-ajax />

    <div class="container">
        <div class="card">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                <i class="svg-icon"><x-icons name="moon" /></i>
            </button>

            <div class="brand">
                <div class="logo">
                    <i class="svg-icon"><x-icons name="weight" /></i>
                </div>
                <div class="brand-text">
                    <h1>Vehicle Weight Management</h1>
                    <p>Secure access to your account</p>
                </div>
            </div>

            <form id="loginForm" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <input type="text" id="username" name="username" placeholder="Enter your username" required
                            autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="svg-icon"><x-icons name="eye" /></i>
                        </button>
                    </div>
                    <div class="error-message" id="passwordError"></div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <span>Sign In</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.getElementById('loginForm');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const submitBtn = document.getElementById('submitBtn');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordError = document.getElementById('passwordError');
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = themeToggle.querySelector('i');

            /* ----------------- THEME HANDLING ----------------- */
            function initTheme() {
                const savedTheme = localStorage.getItem('theme');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (savedTheme) {
                    document.documentElement.setAttribute('data-theme', savedTheme);
                } else {
                    document.documentElement.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
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
                themeIcon.innerHTML = currentTheme === 'dark'
                    ? `<x-icons name="sun"/>`
                    : `<x-icons name="moon"/>`;
            }

            themeToggle.addEventListener('click', toggleTheme);
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
                    updateThemeIcon();
                }
            });
            initTheme();

            /* ----------------- PASSWORD TOGGLE ----------------- */
            togglePasswordBtn.addEventListener('click', function () {
                const isHidden = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
                this.querySelector('i').innerHTML = isHidden
                    ? `<x-icons name="eye-slash"/>`
                    : `<x-icons name="eye"/>`;
            });

            /* ----------------- LOGIN HANDLING ----------------- */
            loginForm.addEventListener('submit', async e => {
                e.preventDefault();
                passwordError.style.display = 'none';

                if (!usernameInput.value.trim()) {
                    showError('Please enter a username');
                    return;
                }

                if (!passwordInput.value) {
                    showError('Please enter a password');
                    return;
                }

                await handleLogin();
            });

            function showError(message) {
                passwordError.textContent = message;
                passwordError.style.display = 'block';
            }

            async function handleLogin() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<div class="spinner"></div><span>Signing in...</span>';

                const fd = new FormData();
                fd.append('username', document.getElementById('username').value);
                fd.append('password', document.getElementById('password').value);

                try {
                    const res = await ajaxRequest('{{ route('login.post') }}', 'POST', fd);

                    if (res.success) {
                        showToast(res.message || 'Signed in successfully', 'success');
                        if (res.redirect) {
                            setTimeout(() => {
                                location.href = res.redirect;
                            }, 2000);
                        }
                    } else {
                        showToast(res.message || 'Invalid credentials', 'error');
                        showError(res.message);
                        console.error(res.message);
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Network error. Please try again.', 'error');
                    showError('Unable to complete your request');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>Sign In</span>';
                }
            }
        });
    </script>
</body>

</html>