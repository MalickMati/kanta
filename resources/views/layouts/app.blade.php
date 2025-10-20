<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Vehicle Weight Management - First Weight')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <style>
    :root {
      --bg-primary: #f8fafc;
      --bg-secondary: #ffffff;
      --sidebar-bg: #1e293b;
      --sidebar-hover: #334155;
      --text-primary: #1e293b;
      --text-secondary: #64748b;
      --text-light: #f8fafc;
      --accent: #3b82f6;
      --accent-hover: #2563eb;
      --border: #e2e8f0;
      --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      --success: #10b981;
      --error: #ef4444;
      --radius: 8px;
      --transition: all 0.3s ease;
      --header-border: rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] {
      --bg-primary: #0f172a;
      --bg-secondary: #1e293b;
      --sidebar-bg: #0f172a;
      --sidebar-hover: #1e293b;
      --text-primary: #f1f5f9;
      --text-secondary: #94a3b8;
      --text-light: #f1f5f9;
      --accent: #60a5fa;
      --accent-hover: #3b82f6;
      --border: #334155;
      --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
      --header-border: rgba(255, 255, 255, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background-color: var(--bg-primary);
      color: var(--text-primary);
      transition: var(--transition);
      overflow-x: hidden;
      user-select: none;
    }

    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }

    .main-content {
      flex: 1;
      margin-left: 260px;
      transition: var(--transition);
      padding: 1.5rem;
      width: calc(100% - 260px);
    }

    @media (max-width: 768px) {      
      .main-content {
        margin-left: 0;
        width: 100%;
        padding: 1rem;
        padding-top: 5rem;
      }
    }
  </style>
  @yield('css')
</head>
<body>
  <x-ajax/>
  <x-toast/>
  <div class="dashboard-container">
    <x-sidebar />

    <main class="main-content">
      @yield('main-content')
    </main>
  </div>

  @yield('script')
</body>
</html>