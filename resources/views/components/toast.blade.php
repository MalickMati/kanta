<div id="toast-container" aria-live="polite"></div>

<style>
  #toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    user-select: none;
  }

  .toast {
    position: relative;
    min-width: 260px;
    max-width: 90vw;
    padding: 14px 18px 18px;
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    opacity: 0;
    transform: translateX(120%);
    animation: slideInRight 0.5s forwards;
  }

  .toast.success { background: #4CAF50; }
  .toast.error   { background: #F44336; }
  .toast.warning { background: #FF9800; }
  .toast.info    { background: #2196F3; }

  .toast svg { width: 28px; height: 28px; flex-shrink: 0; }
  .toast span { flex-grow: 1; }
  .toast button { background: transparent; border: none; color: inherit; font-weight: bold; cursor: pointer; }

  .toast .progress {
    position: absolute;
    bottom: 0; left: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.7);
    animation: shrink linear forwards;
  }

  @keyframes shrink { from { width: 100%; } to { width: 0%; } }

  @keyframes slideInRight {
    0% { opacity: 0; transform: translateX(120%); }
    60% { opacity: 1; transform: translateX(-5%); }
    80% { transform: translateX(3%); }
    100% { opacity: 1; transform: translateX(0); }
  }

  @keyframes slideOutRight {
    0% { opacity: 1; transform: translateX(0); }
    100% { opacity: 0; transform: translateX(120%); }
  }

  .toast.hide { animation: slideOutRight 0.5s forwards; }

  @keyframes bounceScale { 0%{transform:scale(0)} 70%{transform:scale(1.2)} 100%{transform:scale(1)} }
  @keyframes shake { 0%,100%{transform:rotate(0)} 20%{transform:rotate(-15deg)} 40%{transform:rotate(15deg)} 60%{transform:rotate(-10deg)} 80%{transform:rotate(10deg)} }
  @keyframes wiggle { 0%,100%{transform:rotate(0)} 25%{transform:rotate(-5deg)} 75%{transform:rotate(5deg)} }
  @keyframes spinIn { 0%{transform:scale(0) rotate(-180deg)} 70%{transform:scale(1.2) rotate(15deg)} 100%{transform:scale(1) rotate(0)} }

  .animate-success { animation: bounceScale 0.6s ease forwards; }
  .animate-error   { animation: shake 0.6s ease forwards; }
  .animate-warning { animation: wiggle 0.8s ease forwards; }
  .animate-info    { animation: spinIn 0.6s ease forwards; }
</style>

<script>
  (function() {
    const MAX_VISIBLE = 4;
    const VISIBLE = new Set();
    const QUEUE = [];

    const STATE = { wasOffline: !navigator.onLine };

    function flushQueue() {
      while (VISIBLE.size < MAX_VISIBLE && QUEUE.length > 0) {
        const next = QUEUE.shift();
        displayToast(next.message, next.type, next.time);
      }
    }

    function createToastController(toastEl, progressEl, durationMs, onDone) {
      let timerId = null;
      let endAt = Date.now() + durationMs;
      let remaining = durationMs;
      const pauses = new Set();

      function startTimer() {
        clearTimeout(timerId);
        timerId = setTimeout(close, remaining);
        progressEl.style.animationDuration = durationMs + "ms";
        progressEl.style.animationPlayState = "running";
        toastEl.classList.remove("hide");
      }

      function pause(reason) {
        if (pauses.has(reason)) return;
        pauses.add(reason);
        clearTimeout(timerId);
        remaining = Math.max(0, endAt - Date.now());
        progressEl.style.animationPlayState = "paused";
      }

      function resume(reason) {
        if (!pauses.has(reason)) return;
        pauses.delete(reason);
        if (pauses.size > 0) return;
        endAt = Date.now() + remaining;
        startTimer();
      }

      function close() {
        clearTimeout(timerId);
        toastEl.classList.add("hide");
        setTimeout(() => {
          toastEl.remove();
          VISIBLE.delete(api);
          onDone && onDone();
          flushQueue();
        }, 500);
      }

      // init
      startTimer();
      if (document.hidden) pause("visibility");

      const api = { pause, resume, close };
      return api;
    }

    function displayToast(message, type = "info", time = 3000) {
      const container = document.getElementById("toast-container");

      const toast = document.createElement("div");
      toast.className = `toast ${type}`;
      toast.setAttribute("role", (type === "error" || type === "warning") ? "alert" : "status");

      const index = container.children.length;
      toast.style.animationDelay = `${index * 0.1}s`;

      let icon = "";
      if (type === "success") icon = `<div class="animate-success"><x-icons name="success"></x-icons></div>`;
      else if (type === "error") icon = `<div class="animate-error"><x-icons name="error"></x-icons></div>`;
      else if (type === "warning") icon = `<div class="animate-warning"><x-icons name="warning"></x-icons></div>`;
      else icon = `<div class="animate-info"><x-icons name="info"></x-icons></div>`;

      toast.innerHTML = `
        ${icon}
        <span>${message}</span>
        <button class="toast-close" type="button" aria-label="Dismiss">x</button>
      `;

      const progress = document.createElement("div");
      progress.className = "progress";
      progress.style.animationDuration = time + "ms";
      toast.appendChild(progress);

      container.appendChild(toast);

      const ctrl = createToastController(toast, progress, time, () => {});
      VISIBLE.add(ctrl);

      toast.addEventListener("mouseenter", () => ctrl.pause("hover"));
      toast.addEventListener("mouseleave", () => ctrl.resume("hover"));
      toast.querySelector(".toast-close").addEventListener("click", () => ctrl.close());

      return ctrl;
    }

    // Public API
    window.showToast = function(message, type = "info", time = 3000) {
      if (VISIBLE.size >= MAX_VISIBLE) {
        QUEUE.push({ message, type, time });
        return { queued: true };
      }
      return displayToast(message, type, time);
    };

    function pauseAll(reason) { VISIBLE.forEach(t => t.pause(reason)); }
    function resumeAll(reason) { VISIBLE.forEach(t => t.resume(reason)); }

    // Pause timers only when tab is hidden, resume when visible
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) pauseAll("visibility");
      else resumeAll("visibility");
    });

    // Show offline and online toasts
    window.addEventListener("offline", () => {
      if (!STATE.wasOffline) {
        STATE.wasOffline = true;
        showToast("You are offline", "warning", 5000);
      }
    });

    window.addEventListener("online", () => {
      if (STATE.wasOffline) {
        STATE.wasOffline = false;
        showToast("Back online", "success", 3000);
      }
    });

    // If page loads while offline, inform immediately
    document.addEventListener("DOMContentLoaded", () => {
      if (STATE.wasOffline) {
        showToast("You are offline", "warning", 5000);
      }

      @if(session('success'))
        showToast(@json(session('success')), 'success');
      @endif
      @if(session('error'))
        showToast(@json(session('error')), 'error');
      @endif
      @if(session('warning'))
        showToast(@json(session('warning')), 'warning');
      @endif
      @if(session('info'))
        showToast(@json(session('info')), 'info');
      @endif
      @if ($errors->any())
        @foreach ($errors->all() as $error)
          showToast(@json($error), 'error', 6000);
        @endforeach
      @endif
    });
  })();
</script>