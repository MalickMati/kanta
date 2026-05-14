<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        :root {
            --bg-primary: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --bg-card: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Dark mode support based on system preference */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #0f172a;
                --text-primary: #f1f5f9;
                --text-secondary: #94a3b8;
                --bg-card: #1e293b;
                --border: #334155;
            }
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
            padding: 20px;
        }

        .error-card {
            background-color: var(--bg-card);
            border-radius: 12px;
            padding: 3rem;
            max-width: 500px;
            width: 100%;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .error-code {
            font-size: 6rem;
            font-weight: 800;
            color: var(--accent);
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }

        .error-message {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .error-description {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            background-color: var(--accent);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .illustration {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="illustration">
            @yield('illustration')
        </div>
        <div class="error-code">@yield('code')</div>
        <div class="error-message">@yield('message')</div>
        <div class="error-description">@yield('description')</div>
        <a href="{{ url('/') }}" class="btn-home">Return to Homepage</a>
    </div>
</body>
</html>
