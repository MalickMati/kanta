<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Al Hamad Computerized Kanta — Mobile Ticket</title>

<!-- Tailwind (CDN) -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
  /* Animated background gradient */
  @keyframes gradientShift {
    0% { background-position: 0% 40% }
    50%{ background-position: 100% 60% }
    100%{ background-position: 0% 40% }
  }
  /* Fade and slide up */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px) scale(.98) }
    to   { opacity: 1; transform: translateY(0)    scale(1) }
  }
  /* Gentle float for the card */
  @keyframes floatCard {
    0%,100% { transform: translateY(0) }
    50%     { transform: translateY(-3px) }
  }
  /* Shimmer line */
  @keyframes shimmer {
    0% { transform: translateX(-100%) }
    100% { transform: translateX(100%) }
  }

  /* Utility hooks triggered by JS */
  [data-animate]{ opacity:0; transform:translateY(6px) }
  .in-view{ animation: fadeUp .5s cubic-bezier(.2,.7,.2,1) forwards }

  /* Press ripple */
  .ripple { position: relative; overflow: hidden }
  .ripple:after{
    content:"";
    position:absolute; inset:auto;
    width:0; height:0; border-radius:9999px;
    background:rgba(255,255,255,.35);
    transform: translate(-50%,-50%);
    pointer-events:none;
    opacity:0;
  }
  .ripple.active:after{
    animation: rippleAnim .55s ease-out;
  }
  @keyframes rippleAnim{
    0%{ width:0; height:0; opacity:.55 }
    100%{ width:360px; height:360px; opacity:0 }
  }
</style>
</head>
<body class="min-h-screen bg-slate-950 selection:bg-emerald-300/30 selection:text-emerald-900">

  <!-- Animated gradient canvas -->
  <div class="fixed inset-0 -z-10 bg-[length:200%_200%] bg-gradient-to-br from-cyan-600 via-indigo-800 to-emerald-700 opacity-30"
       style="animation: gradientShift 18s ease-in-out infinite;"></div>

  <!-- Subtle radial glows -->
  <div class="fixed inset-0 -z-10 pointer-events-none">
    <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-cyan-400/20 blur-3xl"></div>
    <div class="absolute -bottom-28 -right-16 h-72 w-72 rounded-full bg-emerald-400/20 blur-3xl"></div>
  </div>

  <main class="mx-auto w-full max-w-sm px-3 py-4">
    <!-- Ticket Card -->
    <article
      class="relative rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md shadow-[0_20px_60px_-20px_rgba(0,0,0,.6)]"
      style="animation: floatCard 6s ease-in-out infinite"
      role="region" aria-label="Weighbridge ticket">

      <!-- Glint -->
      <div class="pointer-events-none absolute inset-x-0 top-0 h-10">
        <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent"></div>
      </div>

      <!-- Header -->
      <header class="relative p-4 pb-3" data-animate>
        <div class="flex items-center justify-between gap-3">
          <div class="min-w-0">
            <h1 class="text-base font-extrabold tracking-tight text-cyan-100">
              AL HAMAD COMPUTERIZED KANTA
            </h1>
            <p class="mt-1 truncate text-[13px] text-cyan-100/70">
              Usman Rice Mill Hafizabad Road, Vanike Tarar (0315-5568175)
            </p>
          </div>

          <div class="shrink-0">
            <span class="inline-flex items-center rounded-full border border-cyan-300/40 bg-cyan-300/10 px-3 py-1 text-[12px] font-extrabold text-cyan-100 ring-1 ring-white/5">
              Serial: {{ $record->id }}
            </span>
          </div>
        </div>

        <!-- Accent bar with shimmer -->
        <div class="relative mt-3 h-[3px] overflow-hidden rounded-full bg-gradient-to-r from-cyan-500/40 via-emerald-500/40 to-transparent">
          <span class="absolute inset-y-0 -translate-x-full w-1/3 bg-white/30 blur-[2px]"
                style="animation: shimmer 2.2s linear infinite;"></span>
        </div>
      </header>

      <!-- Customer & Vehicle -->
      <section class="px-4 pt-2" data-animate>
        <div class="mb-2 text-[12px] font-bold uppercase tracking-wide text-cyan-100/70">Customer and vehicle</div>
        <ul class="space-y-2">
          <li class="flex gap-3">
            <span class="shrink-0 text-[13px] font-semibold text-cyan-200/80">Customer</span>
            <span class="min-w-0 flex-1 text-[15px] font-bold text-cyan-50">{{ $record->party }}</span>
          </li>
          <li class="flex gap-3">
            <span class="shrink-0 text-[13px] font-semibold text-cyan-200/80">Vehicle #</span>
            <span class="text-[15px] font-bold text-cyan-50">{{ $record->vehicle_number }}</span>
          </li>
          <li class="flex gap-3">
            <span class="shrink-0 text-[13px] font-semibold text-cyan-200/80">Operator #</span>
            <span class="text-[15px] font-bold text-cyan-50">{{ $user->phone }}</span>
          </li>
        </ul>
      </section>

      <!-- Date & Time chips + QR -->
      <section class="px-4 pt-4" data-animate>
        <div class="mb-2 text-[12px] font-bold uppercase tracking-wide text-cyan-100/70">Times</div>
        <div class="grid grid-cols-2 gap-2">
          <!-- First -->
          <div class="rounded-xl border border-white/10 bg-white/5 p-3">
            <div class="text-[11px] font-bold text-cyan-100/70">First date</div>
            <div class="text-sm font-extrabold text-cyan-50">{{ date('Y-m-d', strtotime($record->first_date)) }}</div>
            <div class="mt-1 text-[11px] font-bold text-cyan-100/70">First time</div>
            <div class="text-sm font-extrabold text-cyan-50">{{ date('H:i:s', strtotime($record->first_date)) }}</div>
          </div>

          <!-- Second -->
          <div class="rounded-xl border border-white/10 bg-white/5 p-3">
            <div class="text-[11px] font-bold text-cyan-100/70">Second date</div>
            <div class="text-sm font-extrabold text-cyan-50">
              {{ $record->second_date ? date('Y-m-d', strtotime($record->second_date)) : '-' }}
            </div>
            <div class="mt-1 text-[11px] font-bold text-cyan-100/70">Second time</div>
            <div class="text-sm font-extrabold text-cyan-50">
              {{ $record->second_date ? date('H:i:s', strtotime($record->second_date)) : '-' }}
            </div>
          </div>
        </div>

        <!-- QR + Material summary -->
        <div class="mt-3 flex items-start justify-between gap-3">
          <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
              <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[12px] font-bold text-cyan-50">
                Material: {{ $record->description }}
              </span>
              <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[12px] font-bold text-cyan-50">
                Unit 40 Kg
              </span>
              <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[12px] font-bold text-cyan-50">
                Mound: {{ $record->net_weight ? number_format($record->net_weight / 40, 1) : '-' }}
              </span>
            </div>
          </div>

        </div>
      </section>

      <!-- Weights -->
      <section class="px-4 pt-4" data-animate>
        <div class="mb-2 text-[12px] font-bold uppercase tracking-wide text-cyan-100/70">Weights</div>
        <div class="grid grid-cols-3 gap-2">
          <!-- 1st -->
          <div class="rounded-xl border border-white/10 bg-white/5 p-3 text-center">
            <div class="text-[11px] font-bold text-cyan-100/70">First</div>
            <div class="text-lg font-black tracking-tight text-cyan-50">{{ $record->first_weight }} <span class="text-[11px] font-extrabold text-cyan-200/70">KG</span></div>
          </div>
          <!-- 2nd -->
          <div class="rounded-xl border border-white/10 bg-white/5 p-3 text-center">
            <div class="text-[11px] font-bold text-cyan-100/70">Second</div>
            <div class="text-lg font-black tracking-tight text-cyan-50">
              {{ $record->second_weight ? "$record->second_weight KG" : '-' }}
            </div>
          </div>
          <!-- Net -->
          <div class="rounded-xl border border-rose-300/30 bg-rose-300/10 p-3 text-center">
            <div class="text-[11px] font-bold text-rose-100/80">Net</div>
            <div class="text-lg font-black tracking-tight text-rose-50">
              {{ $record->net_weight ? "$record->net_weight KG" : '-' }}
            </div>
          </div>
        </div>
      </section>

      <!-- Amount banner -->
      <section class="px-4 pt-4" data-animate>
        <button
          id="amountBtn"
          class="ripple group relative w-full rounded-2xl border border-emerald-400/30 bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 px-4 py-3 text-left shadow-inner ring-1 ring-white/5 transition
                 hover:from-emerald-500/30 hover:to-cyan-500/30 active:scale-[.99]">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-[12px] font-bold uppercase tracking-wide text-emerald-100/80">Amount received</div>
              <div class="mt-0.5 text-xl font-black leading-none text-emerald-100">
                {{ $record->amount }} <span class="text-sm font-extrabold text-emerald-200/80">Rs</span>
              </div>
            </div>
            <svg class="h-6 w-6 text-emerald-200/80 transition group-hover:rotate-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M12 3v18M5 10l7-7 7 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </button>
        <p class="mt-2 px-1 text-center text-[12px] font-medium text-cyan-100/70">
          Received with thanks
        </p>
      </section>

      <!-- Footer -->
      <footer class="p-4 pt-2" data-animate>
        <div class="rounded-xl border border-white/10 bg-white/5 p-3 text-center text-[12px] text-cyan-100/70">
          Verified by system • Thank you for visiting Al Hamad Computerized Kanta
        </div>
      </footer>

      <!-- Subtle bottom fade -->
      <div class="pointer-events-none absolute inset-x-0 bottom-0 h-10 bg-gradient-to-t from-black/10 to-transparent"></div>
    </article>
  </main>

  <script>
    // Staggered reveal on scroll
    const items = document.querySelectorAll('[data-animate]');
    const io = new IntersectionObserver((entries)=>{
      entries.forEach((e)=>{
        if(e.isIntersecting){
          e.target.classList.add('in-view');
          io.unobserve(e.target);
        }
      })
    }, { threshold: 0.15 });

    items.forEach((el,i)=>{
      el.style.animationDelay = (0.05 + i*0.05) + 's';
      io.observe(el);
    });

    // Ripple on amount touch
    const btn = document.getElementById('amountBtn');
    btn.addEventListener('pointerdown', (ev)=>{
      const r = btn.getBoundingClientRect();
      btn.style.setProperty('--rx', (ev.clientX - r.left) + 'px');
      btn.style.setProperty('--ry', (ev.clientY - r.top) + 'px');
      btn.classList.remove('active'); // restart
      void btn.offsetWidth;
      btn.classList.add('active');
    }, {passive:true});
    btn.addEventListener('animationend', ()=> btn.classList.remove('active'));
  </script>
</body>
</html>
