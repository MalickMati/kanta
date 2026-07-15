@extends('layouts.app')

@section('title', config('app.name') . ' - Scale Monitor')

@section('css')
  <style>
    .monitor-container {
      max-width: 1000px;
      margin: 0 auto;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .card {
      background: var(--bg-secondary);
      border-radius: var(--radius);
      padding: 1.5rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
    }

    .card-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid var(--border);
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .status-badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .status-badge.connected {
      background-color: rgba(16, 185, 129, 0.1);
      color: var(--success);
      border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-badge.disconnected {
      background-color: rgba(239, 68, 68, 0.1);
      color: var(--error);
      border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .live-weight {
      font-size: 3rem;
      font-weight: 700;
      color: var(--accent);
      text-align: center;
      margin: 1rem 0;
      font-family: monospace;
    }

    .ports-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .port-item {
      padding: 0.75rem;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      margin-bottom: 0.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .port-path {
      font-weight: 600;
      color: var(--text-primary);
      font-family: monospace;
    }

    .port-desc {
      font-size: 0.875rem;
      color: var(--text-secondary);
    }

    .terminal-card {
      grid-column: span 2;
    }

    .terminal {
      background: #0f172a;
      border-radius: var(--radius);
      padding: 1rem;
      height: 400px;
      overflow-y: auto;
      font-family: 'Courier New', Courier, monospace;
      font-size: 0.9rem;
      color: #38bdf8;
      border: 1px solid #1e293b;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
    }

    .terminal-line {
      margin-bottom: 0.25rem;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      padding-bottom: 2px;
      word-break: break-all;
    }

    .terminal-time {
      color: #64748b;
      margin-right: 0.5rem;
    }

    .terminal-raw {
      color: #f8fafc;
    }

    .refresh-btn {
      background: var(--accent);
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: var(--radius);
      cursor: pointer;
      font-size: 0.875rem;
      transition: var(--transition);
    }

    .refresh-btn:hover {
      background: var(--accent-hover);
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
      .dashboard-grid {
        grid-template-columns: 1fr;
      }
      .terminal-card {
        grid-column: span 1;
      }
    }
  </style>
@endsection

@section('main-content')
  <x-header heading="Scale Monitor" para="Real-time hardware status and data stream logging" />

  <div class="monitor-container">
    
    <div class="dashboard-grid">
      <!-- Status Card -->
      <div class="card">
        <h3 class="card-title">
          Scale Status
        </h3>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
          <span class="status-badge disconnected" id="connectionBadge">Disconnected</span>
          <span id="statusText" style="font-size: 0.9rem; color: var(--text-secondary);">Waiting for service...</span>
        </div>
        
        <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 1rem;">
          <p style="text-align: center; color: var(--text-secondary); margin-bottom: 0.5rem;">Latest Parsed Weight</p>
          <div class="live-weight" id="liveWeightDisplay">---</div>
          <p style="text-align: center; color: var(--text-secondary);">kilograms</p>
        </div>
      </div>

      <!-- Ports Card -->
      <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">
          <h3 class="card-title" style="border: none; margin: 0; padding: 0;">Available Ports</h3>
          <button class="refresh-btn" id="refreshPortsBtn">Refresh List</button>
        </div>
        
        <ul class="ports-list" id="portsList">
          <li class="port-item" style="justify-content: center; color: var(--text-secondary);">Loading ports...</li>
        </ul>
      </div>

      <!-- Live Log Terminal -->
      <div class="card terminal-card">
        <h3 class="card-title">Live Data Stream (Raw DB9/USB Output)</h3>
        <div class="terminal" id="terminalLog">
          <!-- Logs will appear here -->
        </div>
      </div>
    </div>

  </div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const connectionBadge = document.getElementById('connectionBadge');
      const statusText = document.getElementById('statusText');
      const liveWeightDisplay = document.getElementById('liveWeightDisplay');
      const portsList = document.getElementById('portsList');
      const terminalLog = document.getElementById('terminalLog');
      const refreshPortsBtn = document.getElementById('refreshPortsBtn');

      let logCount = 0;
      const MAX_LOGS = 200;

      function appendLog(rawData) {
        // Ensure string and remove extra newlines
        let text = "";
        if(rawData instanceof ArrayBuffer || ArrayBuffer.isView(rawData)) {
            // Convert Buffer to string if necessary
            text = new TextDecoder().decode(new Uint8Array(rawData));
        } else {
            text = String(rawData);
        }
        
        text = text.replace(/[\r\n]+/g, ''); // Strip trailing newlines for cleaner logging

        if (!text) return;

        const time = new Date().toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit', fractionalSecondDigits: 2 });
        
        const line = document.createElement('div');
        line.className = 'terminal-line';
        line.innerHTML = `<span class="terminal-time">[${time}]</span><span class="terminal-raw">${escapeHTML(text)}</span>`;
        
        terminalLog.appendChild(line);
        logCount++;

        // Keep terminal scroll at bottom
        terminalLog.scrollTop = terminalLog.scrollHeight;

        // Prevent memory leak by removing old logs
        if (logCount > MAX_LOGS) {
            terminalLog.removeChild(terminalLog.firstChild);
            logCount--;
        }
      }

      function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag)
        );
      }

      if (typeof io !== 'undefined') {
        const socket = io('http://localhost:3001');

        socket.on('connect', () => {
            appendLog('--- CONNECTED TO SCALE MICROSERVICE ---');
            socket.emit('get_ports');
        });

        socket.on('disconnect', () => {
            appendLog('--- DISCONNECTED FROM SCALE MICROSERVICE ---');
            connectionBadge.className = 'status-badge disconnected';
            connectionBadge.textContent = 'Service Offline';
            statusText.textContent = 'Check PM2 or Node.js service';
        });

        socket.on('scale_status', (msg) => {
            appendLog(`[STATUS] ${msg}`);
            statusText.textContent = msg;
            
            if (msg.includes('Connected')) {
                connectionBadge.className = 'status-badge connected';
                connectionBadge.textContent = 'Connected';
            } else {
                connectionBadge.className = 'status-badge disconnected';
                connectionBadge.textContent = 'Disconnected';
            }
        });

        socket.on('scale_error', (msg) => {
            appendLog(`[ERROR] ${msg}`);
            if(typeof showToast === 'function') {
                showToast("Scale Error: " + msg, "error");
            }
        });

        socket.on('weight_reading', (weight) => {
            liveWeightDisplay.textContent = weight;
        });

        socket.on('raw_data', (data) => {
            appendLog(data);
        });

        socket.on('ports_list', (ports) => {
            portsList.innerHTML = '';
            
            if (!ports || ports.length === 0) {
                portsList.innerHTML = '<li class="port-item" style="justify-content: center; color: var(--error);">No serial devices found on system</li>';
                return;
            }

            ports.forEach(port => {
                const li = document.createElement('li');
                li.className = 'port-item';
                
                const desc = port.manufacturer ? port.manufacturer : (port.pnpId ? 'PnP Device' : 'Standard Serial Port');
                
                li.innerHTML = `
                    <span class="port-path">${port.path}</span>
                    <span class="port-desc">${desc}</span>
                `;
                portsList.appendChild(li);
            });
        });

        refreshPortsBtn.addEventListener('click', () => {
            socket.emit('get_ports');
            refreshPortsBtn.textContent = 'Refreshing...';
            setTimeout(() => { refreshPortsBtn.textContent = 'Refresh List'; }, 500);
        });

      } else {
        appendLog('ERROR: Socket.io client script not loaded. Ensure Node service is running and layout includes socket.io.js');
      }
    });
  </script>
@endsection
