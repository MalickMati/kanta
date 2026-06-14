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

    .print-modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .print-modal-card {
      width: 100%;
      max-width: 400px;
      margin: 1rem;
      background: var(--bg-secondary);
      border-radius: var(--radius);
      padding: 2rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
    }
    .print-modal-card .form-actions {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 1px solid var(--border);
    }
    .print-modal-card .btn {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: var(--radius);
      font-weight: 500;
      cursor: pointer;
    }
    .print-modal-card .btn-primary { background: var(--accent); color: white; }
    .print-modal-card .btn-secondary { background: var(--bg-primary); color: var(--text-primary); border: 1px solid var(--border); }
    .print-modal-card input { width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: var(--radius); }
    .print-modal-card label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
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

  <!-- Silent Print Modal -->
  <div id="silentPrintModal" class="print-modal-overlay" style="display: none;">
    <div class="print-modal-card">
      <h2 style="margin-bottom: 1rem; font-size: 1.25rem;">Print Confirmation</h2>
      <div style="margin-bottom: 1rem;">
        <label for="printCopies">How many copies do you want to print?</label>
        <input type="number" id="printCopies" value="1" min="1" max="10">
      </div>
      <div class="form-actions">
        @if (Auth::user()->role == 'admin')
          <button type="button" class="btn btn-secondary" id="savePdfBtn" style="background: var(--success); color: white; margin-right: auto;">Save as PDF</button>
        @endif
        <button type="button" class="btn btn-secondary" id="cancelPrintBtn">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmPrintBtn">Print</button>
      </div>
    </div>
  </div>

  <script>
    window.showSilentPrintModal = function(redirectUrl) {
      return new Promise((resolve) => {
        const modal = document.getElementById('silentPrintModal');
        const copiesInput = document.getElementById('printCopies');
        const confirmBtn = document.getElementById('confirmPrintBtn');
        const cancelBtn = document.getElementById('cancelPrintBtn');
        const savePdfBtn = document.getElementById('savePdfBtn');

        modal.style.display = 'flex';
        copiesInput.value = '1';
        copiesInput.focus();

        const handleConfirm = async () => {
          const copies = parseInt(copiesInput.value) || 1;
          cleanup();
          
          try {
            // Force layout4 as per requirements
            const finalUrl = new URL(redirectUrl);
            finalUrl.searchParams.set('layout', 'layout4');
            
            if (typeof showToast === 'function') {
                showToast('Fetching print layout...', 'success');
            }
            
            const response = await fetch(finalUrl.toString());
            const html = await response.text();
            
            if (typeof showToast === 'function') {
                showToast('Sending to printer...', 'success');
            }
            
            const printResponse = await fetch('http://localhost:3000/print', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ html, quantity: copies })
            });
            
            const printResult = await printResponse.json();
            
            if (printResult.success) {
              if (typeof showToast === 'function') showToast(printResult.message, 'success');
            } else {
              if (typeof showToast === 'function') showToast('Print failed: ' + printResult.error, 'error');
            }
          } catch (error) {
            console.error(error);
            if (typeof showToast === 'function') showToast('Could not connect to local printer service', 'error');
          }
          resolve(true);
        };

        const handleCancel = () => {
          cleanup();
          resolve(false);
        };

        const handleSavePdf = () => {
          cleanup();
          const finalUrl = new URL(redirectUrl);
          finalUrl.searchParams.set('layout', 'layout4');
          const printWindow = window.open(finalUrl.toString(), '_blank');
          if (!printWindow && typeof showToast === 'function') {
            showToast('Please allow popups to save as PDF', 'error');
          }
          resolve(true);
        };

        const cleanup = () => {
          modal.style.display = 'none';
          confirmBtn.removeEventListener('click', handleConfirm);
          cancelBtn.removeEventListener('click', handleCancel);
          if (savePdfBtn) savePdfBtn.removeEventListener('click', handleSavePdf);
        };

        // Also allow Enter to confirm
        const handleKeydown = (e) => {
          if (e.key === 'Enter') handleConfirm();
          if (e.key === 'Escape') handleCancel();
        };

        confirmBtn.addEventListener('click', handleConfirm);
        cancelBtn.addEventListener('click', handleCancel);
        if (savePdfBtn) savePdfBtn.addEventListener('click', handleSavePdf);
        copiesInput.addEventListener('keydown', handleKeydown);
      });
    };
  </script>

  @yield('script')
</body>
</html>