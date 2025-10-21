@extends('layouts.app')

@section('title', config('app.name') . ' - Profile')

@section('css')
  <style>
    :root{
      --radius-lg:16px; --radius:12px; --radius-sm:10px;
      --ring:0 0 0 2px rgba(99,102,241,.15);
      --glass:linear-gradient(180deg,rgba(255,255,255,.6),rgba(255,255,255,.35));
      --glass-dark:linear-gradient(180deg,rgba(255,255,255,.06),rgba(255,255,255,.02));
    }
    .profile-grid{
      display:grid;
      grid-template-columns:2fr 1fr;
      gap:1.25rem;
      align-items:start;
    }
    .card{
      position:relative; background:var(--bg-secondary);
      border-radius:var(--radius-lg); padding:1.25rem;
      border:1px solid var(--border);
      box-shadow:0 1px 2px rgba(0,0,0,.06),0 8px 24px rgba(0,0,0,.06);
      transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease, background-color .22s ease;
      backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px);
      overflow:hidden; isolation:isolate;
    }
    .card::after{
      content:""; position:absolute; inset:0; pointer-events:none;
      background:radial-gradient(1200px 300px at -10% -10%, rgba(99,102,241,.12), transparent 40%),
                 radial-gradient(1200px 300px at 110% 110%, rgba(236,72,153,.10), transparent 40%);
      opacity:.6; z-index:0;
    }
    .card:hover{ transform:translateY(-2px);
      box-shadow:0 3px 10px rgba(0,0,0,.06), 0 16px 40px rgba(0,0,0,.08);
      border-color: color-mix(in oklab, var(--accent) 25%, var(--border));
    }

    .section-title{ font-size:1.1rem; font-weight:700; letter-spacing:-.01em; margin:0 0 1rem 0; }
    .form-grid{ display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    .form-row{ display:flex; flex-direction:column; gap:.45rem; }
    label{ font-size:.9rem; font-weight:600; color:var(--text-secondary); }
    input{
      border:1px solid var(--border); border-radius:12px; padding:.8rem .9rem; background:var(--bg-primary);
      color:var(--text-primary); transition:border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
    }
    input:focus{ outline:none; border-color:color-mix(in oklab, var(--accent) 35%, var(--border)); box-shadow:var(--ring); }
    .actions{ display:flex; gap:.6rem; justify-content:flex-end; margin-top:1rem; }
    .btn{
      appearance:none; border:1px solid transparent; border-radius:12px; padding:.75rem 1rem; font-weight:700; cursor:pointer;
      transition:transform .12s ease, box-shadow .12s ease, background-color .12s ease, border-color .12s ease;
    }
    .btn-primary{ background:color-mix(in oklab, var(--accent) 92%, #fff 0%); color:#fff; }
    .btn-primary:hover{ transform:translateY(-1px); }
    .btn-ghost{ background:transparent; border-color:var(--border); color:var(--text-primary); }
    .muted{ color:var(--text-secondary); font-size:.9rem; }
    .danger{ color:#ef4444; font-size:.9rem; }
    .success{
      border:1px solid color-mix(in oklab, #10b981 35%, var(--border));
      background:color-mix(in oklab, #10b981 12%, transparent);
      color:#065f46; padding:.75rem .9rem; border-radius:12px; margin-bottom:1rem; font-weight:600;
    }
    .error{ color:#b91c1c; font-size:.85rem; margin-top:.2rem; }
    .avatar{
      width:84px; height:84px; border-radius:16px; object-fit:cover; box-shadow:0 6px 14px rgba(0,0,0,.14);
      border:1px solid color-mix(in oklab, var(--border) 85%, transparent);
    }
    @media (prefers-color-scheme: light){ .card{ background:var(--glass); } }
    @media (prefers-color-scheme: dark){ .card{ background:var(--glass-dark); } }

    @media (max-width:1024px){ .profile-grid{ grid-template-columns:1fr; } .form-grid{ grid-template-columns:1fr; } }
  </style>
@endsection

@section('main-content')
  <x-header heading="Your Profile" para="Manage your account details and security." />

  <div class="profile-grid">
    {{-- Profile info --}}
    <div class="card">
      <h2 class="section-title">Profile</h2>

      @if (session('profile_updated'))
        <div class="success">{{ session('profile_updated') }}</div>
      @endif

      <form action="{{ route('profile.update') }}" method="POST" class="form" novalidate autocomplete="off">
        @csrf
        @method('PUT')
        <div class="form-grid">
          <div class="form-row">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror
          </div>

          <div class="form-row">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username', Auth::user()->username) }}" required readonly>
            @error('username') <div class="error">{{ $message }}</div> @enderror
          </div>

          <div class="form-row">
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone', Auth::user()->phone) }}">
            @error('phone') <div class="error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="actions">
          <button class="btn btn-ghost" type="reset">Reset</button>
          <button class="btn btn-primary" type="submit">Save changes</button>
        </div>
      </form>
    </div>

    {{-- Password --}}
    <div class="card">
      <h2 class="section-title">Password</h2>

      @if (session('password_updated'))
        <div class="success">{{ session('password_updated') }}</div>
      @endif

      <form action="{{ route('profile.password.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="form-row">
          <label for="current_password">Current password</label>
          <input id="current_password" name="current_password" type="password" required autocomplete="current-password">
          @error('current_password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <label for="password">New password</label>
          <input id="password" name="password" type="password" required autocomplete="new-password">
          @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <label for="password_confirmation">Confirm new password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
        </div>

        <div class="actions">
          <button class="btn btn-primary" type="submit">Update password</button>
        </div>
      </form>

      @if (session('password_error'))
        <p class="danger" style="margin-top:.75rem;">{{ session('password_error') }}</p>
      @endif
    </div>
  </div>
@endsection
