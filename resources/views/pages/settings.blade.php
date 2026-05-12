@extends('layouts.app')

@section('title', config('app.name') . ' - Settings')

@section('css')
  <style>
    :root{
      --radius-lg:16px; --radius:12px; --radius-sm:10px;
      --ring:0 0 0 2px rgba(99,102,241,.15);
      --glass:linear-gradient(180deg,rgba(255,255,255,.6),rgba(255,255,255,.35));
      --glass-dark:linear-gradient(180deg,rgba(255,255,255,.06),rgba(255,255,255,.02));
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
    
    .card-title { font-size:1.1rem; font-weight:700; letter-spacing:-.01em; margin:0 0 1rem 0; text-align: center; }
    
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
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
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
        box-shadow: 0 2px 5px rgba(0,0,0,.2);
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
        background: rgba(255,255,255,0.8);
        z-index: 10;
        font-weight: 600;
        color: var(--text-secondary);
    }

    @media (prefers-color-scheme: light){ .card{ background:var(--glass); } }
    @media (prefers-color-scheme: dark){ .card{ background:var(--glass-dark); } }
  </style>
@endsection

@section('main-content')
  <x-header heading="Settings" para="Customize application preferences." />

  <div class="card" style="margin-top: 1.5rem;">
    <h2 class="card-title">Select Print Layout</h2>
    <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">
        Choose the default layout to use when printing vehicle records. This is saved to your local device.
    </p>

    <div class="layouts-grid">
        <!-- Layout 1 -->
        <div class="card layout-card" data-layout="1" onclick="openPreview('layout1', 'Standard Invoice')">
            <div class="layout-preview-box">
                <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="invoice" /></i>
            </div>
            <h3>Standard Invoice</h3>
            <p>Clean two-column print design</p>
        </div>

        <!-- Layout 2 -->
        <div class="card layout-card" data-layout="2" onclick="openPreview('layout2', 'Modern Ticket')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="refresh" /></i>
            </div>
            <h3>Modern Ticket</h3>
            <p>Compact layout with QR integration</p>
        </div>

        <!-- Layout 3 -->
        <div class="card layout-card" data-layout="3" onclick="openPreview('layout3', 'Gradient Header')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="print" /></i>
            </div>
            <h3>Gradient Header</h3>
            <p>Centered ticket with color accent</p>
        </div>

        <!-- Layout 4 -->
        <div class="card layout-card" data-layout="4" onclick="openPreview('layout4', 'Classic Kanta')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="weight-meter" /></i>
            </div>
            <h3>Classic Kanta</h3>
            <p>The legacy vehicle weight record</p>
        </div>
        <!-- Layout 5 -->
        <div class="card layout-card" data-layout="5" onclick="openPreview('layout5', 'Elegant Serif')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="file" /></i>
            </div>
            <h3>Elegant Serif</h3>
            <p>Double lines with serif typography</p>
        </div>

        <!-- Layout 6 -->
        <div class="card layout-card" data-layout="6" onclick="openPreview('layout6', 'Dark Header')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="invoice" /></i>
            </div>
            <h3>Dark Header</h3>
            <p>High contrast black header layout</p>
        </div>

        <!-- Layout 7 -->
        <div class="card layout-card" data-layout="7" onclick="openPreview('layout7', 'Two Tone Split')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="template" /></i>
            </div>
            <h3>Two Tone Split</h3>
            <p>Dark sidebar with white content area</p>
        </div>

        <!-- Layout 8 -->
        <div class="card layout-card" data-layout="8" onclick="openPreview('layout8', 'Dotted Matrix')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="print" /></i>
            </div>
            <h3>Dotted Matrix</h3>
            <p>Retro dot-matrix printer receipt style</p>
        </div>

        <!-- Layout 9 -->
        <div class="card layout-card" data-layout="9" onclick="openPreview('layout9', 'Minimalist')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="refresh" /></i>
            </div>
            <h3>Minimalist</h3>
            <p>Borderless clean typography grid</p>
        </div>

        <!-- Layout 10 -->
        <div class="card layout-card" data-layout="10" onclick="openPreview('layout10', 'Rounded Cards')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="template" /></i>
            </div>
            <h3>Rounded Cards</h3>
            <p>Separated rounded card sections</p>
        </div>

        <!-- Layout 11 -->
        <div class="card layout-card" data-layout="11" onclick="openPreview('layout11', 'Large Typography')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="file" /></i>
            </div>
            <h3>Large Typography</h3>
            <p>Huge, bold numbers for readability</p>
        </div>

        <!-- Layout 12 -->
        <div class="card layout-card" data-layout="12" onclick="openPreview('layout12', 'Zebra Striped')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="invoice" /></i>
            </div>
            <h3>Zebra Striped</h3>
            <p>Horizontal table with shaded rows</p>
        </div>

        <!-- Layout 13 -->
        <div class="card layout-card" data-layout="13" onclick="openPreview('layout13', 'Right Aligned')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="weight-meter" /></i>
            </div>
            <h3>Right Aligned</h3>
            <p>Dotted leaders with values flushed right</p>
        </div>

        <!-- Layout 14 -->
        <div class="card layout-card" data-layout="14" onclick="openPreview('layout14', 'Boxed Wireframe')">
            <div class="layout-preview-box">
                 <i class="svg-icon" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.3;"><x-icons name="template" /></i>
            </div>
            <h3>Boxed Wireframe</h3>
            <p>Architectural thick black boxed grids</p>
        </div>
    </div>
  </div>

  <!-- Preview Modal -->
  <div class="modal-overlay" id="previewModal">
      <div class="modal-content">
          <div class="modal-header">
              <h3 id="modalTitle">Layout Preview</h3>
              <button class="close-modal" onclick="closePreview()">
                  <i class="svg-icon"><x-icons name="close" /></i>
              </button>
          </div>
          <div class="modal-body">
              <div class="loader" id="previewLoader">Loading preview...</div>
              <iframe id="previewFrame" class="preview-iframe" onload="document.getElementById('previewLoader').style.display='none'"></iframe>
          </div>
          <div class="modal-footer">
              <button class="btn btn-secondary" onclick="closePreview()">Cancel</button>
              <button class="btn btn-primary" id="selectLayoutBtn">Use This Layout</button>
          </div>
      </div>
  </div>

@endsection

@section('script')
<script>
    const STORAGE_KEY = 'kanta_print_layout';
    let currentPreviewLayout = null;

    document.addEventListener('DOMContentLoaded', () => {
        // Load saved layout or default to 1
        const savedLayout = localStorage.getItem(STORAGE_KEY) || 'layout1';
        highlightSelectedCard(savedLayout);
    });

    function highlightSelectedCard(layoutId) {
        document.querySelectorAll('.layout-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        const number = layoutId.replace('layout', '');
        const cardToSelect = document.querySelector(`.layout-card[data-layout="${number}"]`);
        
        if (cardToSelect) {
            cardToSelect.classList.add('selected');
        }
    }

    function openPreview(layoutId, title) {
        currentPreviewLayout = layoutId;
        
        document.getElementById('modalTitle').innerText = title + ' - Preview';
        document.getElementById('previewLoader').style.display = 'flex';
        
        // Build preview URL
        const previewUrl = `{{ route('print.preview') }}?layout=${layoutId}`;
        document.getElementById('previewFrame').src = previewUrl;
        
        document.getElementById('previewModal').classList.add('active');

        // Setup save button
        document.getElementById('selectLayoutBtn').onclick = () => {
            saveLayoutPreference(layoutId);
            closePreview();
        };
    }

    function closePreview() {
        document.getElementById('previewModal').classList.remove('active');
        // Clear iframe to stop lingering downloads/activity
        setTimeout(() => {
            document.getElementById('previewFrame').src = 'about:blank';
        }, 300);
    }

    function saveLayoutPreference(layoutId) {
        localStorage.setItem(STORAGE_KEY, layoutId);
        highlightSelectedCard(layoutId);
    }

    // Close modal on outside click
    document.getElementById('previewModal').addEventListener('click', (e) => {
        if(e.target === document.getElementById('previewModal')) {
            closePreview();
        }
    });
</script>
@endsection
