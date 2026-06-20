@extends('layouts.app')

@section('title', config('app.name') . ' - Settings')

@section('css')
    <style>
        :root {
            --radius-lg: 16px;
            --radius: 12px;
            --radius-sm: 10px;
            --ring: 0 0 0 2px rgba(99, 102, 241, .15);
            --glass: linear-gradient(180deg, rgba(255, 255, 255, .6), rgba(255, 255, 255, .35));
            --glass-dark: linear-gradient(180deg, rgba(255, 255, 255, .06), rgba(255, 255, 255, .02));
        }

        .card {
            position: relative;
            background: var(--bg-secondary);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            border: 1px solid var(--border);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .06), 0 8px 24px rgba(0, 0, 0, .06);
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease, background-color .22s ease;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            overflow: hidden;
            isolation: isolate;
        }

        .card::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(1200px 300px at -10% -10%, rgba(99, 102, 241, .12), transparent 40%),
                radial-gradient(1200px 300px at 110% 110%, rgba(236, 72, 153, .10), transparent 40%);
            opacity: .6;
            z-index: 0;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -.01em;
            margin: 0 0 1rem 0;
            text-align: center;
        }

        .layouts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .layout-card {
            cursor: pointer;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .layout-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
            border-color: color-mix(in oklab, var(--accent) 30%, var(--border));
        }

        /* Selected State */
        .layout-card.selected {
            border-color: var(--accent);
            background: color-mix(in oklab, var(--accent) 5%, var(--bg-secondary));
        }

        .layout-card.selected::before {
            content: "✓";
            position: absolute;
            top: 10px;
            right: 15px;
            color: white;
            background: var(--accent);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .2);
        }

        .layout-preview-box {
            width: 100%;
            height: 180px;
            background: var(--bg-primary);
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            overflow: hidden;
            position: relative;
        }

        .layout-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
        }

        .layout-card h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
            color: var(--text-primary);
        }

        .layout-card p {
            margin: 0;
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--bg-secondary);
            width: 90%;
            max-width: 900px;
            height: 85vh;
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            transform: scale(0.95);
            transition: transform 0.2s ease;
            overflow: hidden;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .close-modal:hover {
            background: var(--bg-primary);
            color: var(--error);
        }

        .modal-body {
            flex: 1;
            padding: 0;
            background: #f1f5f9;
            overflow: hidden;
            position: relative;
        }

        /* Iframe wrapper for perfect A4 scaling */
        .preview-iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: #fff;
        }

        .modal-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: var(--bg-secondary);
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--bg-primary);
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .loader {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.8);
            z-index: 10;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-row {
            display: flex;
            flex-direction: column;
            gap: .45rem;
        }

        label {
            font-size: .9rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        input {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .8rem .9rem;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
        }

        input:focus {
            outline: none;
            border-color: color-mix(in oklab, var(--accent) 35%, var(--border));
            box-shadow: var(--ring);
        }

        .actions {
            display: flex;
            gap: .6rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .btn {
            appearance: none;
            border: 1px solid transparent;
            border-radius: 12px;
            padding: .75rem 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .12s ease, box-shadow .12s ease, background-color .12s ease, border-color .12s ease;
        }

        .btn-primary {
            background: color-mix(in oklab, var(--accent) 92%, #fff 0%);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: transparent;
            border-color: var(--border);
            color: var(--text-primary);
        }

        .muted {
            color: var(--text-secondary);
            font-size: .9rem;
        }

        .danger {
            color: #ef4444;
            font-size: .9rem;
        }

        .success {
            border: 1px solid color-mix(in oklab, #10b981 35%, var(--border));
            background: color-mix(in oklab, #10b981 12%, transparent);
            color: #065f46;
            padding: .75rem .9rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .error {
            color: #b91c1c;
            font-size: .85rem;
            margin-top: .2rem;
        }

        @media (prefers-color-scheme: light) {
            .card {
                background: var(--glass);
            }
        }

        @media (prefers-color-scheme: dark) {
            .card {
                background: var(--glass-dark);
            }
        }
    </style>
@endsection

@section('main-content')
    <x-header heading="Settings" para="Customize application preferences." />

    <div class="card" style="margin-top: 1.5rem;">

        <form id="companynameform" class="form" novalidate autocomplete="off">
            @csrf
            <div class="form-grid">
                <div class="form-row">
                    <label for="companyname">Company Name</label>
                    <input id="companyname" name="companyname" type="text" value="{{ $company->value }}" required>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit" id="companysavebtn">Update Name</button>
                </div>
        </form>

    </div>

    <div style="margin-top: 1.5rem;">

        <form id="addressform" class="form" novalidate autocomplete="off">
            @csrf
            <div class="form-grid">
                <div class="form-row">
                    <label for="address">Address</label>
                    <input id="address" name="address" type="text" value="{{ $address->value }}" required>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit" id="savebtn">Update Address</button>
                </div>
        </form>

    </div>

    <div style="margin-top: 1.5rem;">

        <form id="contactnumform" class="form" novalidate autocomplete="off">
            @csrf
            <div class="form-grid">
                <div class="form-row">
                    <label for="contactnum">Contact Number</label>
                    <input id="contactnum" name="contactnum" type="tel" value="{{ $contact->value ?? 'Not Found' }}" required>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit" id="contactnumbtn">Update Contact</button>
                </div>
        </form>

    </div>

@endsection

@section('script')
    <script>
        const addressform = document.getElementById('addressform');
        const addressbtn = document.getElementById('savebtn');

        const companynameform = document.getElementById('companynameform');
        const companynamebtn = document.getElementById('companysavebtn');

        const contactnumform = document.getElementById('contactnumform');
        const contactnumbtn = document.getElementById('contactnumbtn');

        companynameform.addEventListener('submit', async (e) => {
            e.preventDefault();

            companynamebtn.disabled = true;

            try {
                const formData = new FormData();

                formData.append('companyname', document.getElementById('companyname').value);

                const res = await ajaxRequest('{{ route('company.name.update') }}', 'POST', formData);

                if (res.success) {
                    showToast(res.message, "success");
                } else {
                    showToast(res.message, "warning");
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error', 3000);
            } finally {
                companynamebtn.disabled = false;
            }
        });

        addressform.addEventListener('submit', async (e) => {
            e.preventDefault();

            addressbtn.disabled = true;

            try {
                const formData = new FormData();

                formData.append('address', document.getElementById('address').value);

                const res = await ajaxRequest('{{ route('address.update') }}', 'POST', formData);

                if (res.success) {
                    showToast(res.message, "success");
                } else {
                    showToast(res.message, "warning");
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error', 3000);
            } finally {
                addressbtn.disabled = false;
            }
        });

        contactnumform.addEventListener('submit', async (e) => {
            e.preventDefault();

            contactnumbtn.disabled = true;

            try {
                const formData = new FormData();

                formData.append('contact', document.getElementById('contactnum').value);

                const res = await ajaxRequest('{{ route('contact.number.update') }}', 'POST', formData);

                if (res.success) {
                    showToast(res.message, "success");
                } else {
                    showToast(res.message, "warning");
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error', 3000);
            } finally {
                contactnumbtn.disabled = false;
            }
        });
    </script>
@endsection