
@php
    /* ── Shared class tokens ─────────────────────────── */
    $navBase = 'group relative flex items-center gap-3.5 px-4 py-[11px] rounded-2xl
                font-bold text-sm no-underline overflow-hidden select-none
                transition-all duration-300 ease-out';

    $inactive = 'text-gray-500 hover:text-white hover:bg-white/5 hover:translate-x-1';

    $active   = 'text-white bg-gradient-to-r from-blue-500/20 to-blue-500/5
                 border-l-[3px] border-blue-500 !pl-[13px]
                 shadow-[inset_0_1px_0_rgba(255,255,255,0.04)]';

    $iconBase = 'relative flex-shrink-0 w-9 h-9 rounded-xl
                 flex items-center justify-center text-sm
                 transition-all duration-300';

    $iOff = 'bg-white/6 group-hover:bg-blue-500/15 group-hover:text-blue-400';

    $iOn  = 'bg-gradient-to-br from-blue-500 to-blue-700 text-white
             shadow-[0_0_14px_rgba(59,130,246,0.45)]';

    $lbl  = 'px-4 pt-5 pb-2 text-[9px] font-extrabold text-gray-600
             uppercase tracking-[0.26em] select-none pointer-events-none';

    /* ── Active route helper ─────────────────────────── */
    $r = function (string|array $p): bool {
        return is_array($p)
            ? collect($p)->contains(fn($x) => request()->routeIs($x))
            : request()->routeIs($p);
    };
@endphp

{{-- ══════════════════════════════════════════════════════ --}}
{{-- SIDEBAR                                               --}}
{{-- ══════════════════════════════════════════════════════ --}}
<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 w-72 z-50 flex flex-col
           bg-gradient-to-b from-[#060c1b] via-[#08101e] to-[#040810]
           shadow-[4px_0_64px_rgba(0,0,0,0.7)]
           -translate-x-full lg:translate-x-0
           transition-transform duration-500 ease-[cubic-bezier(0.77,0,0.175,1)]
           overflow-hidden"
    aria-label="Navigasi utama"
>

    {{-- ── Ambient layers ──────────────────────────────── --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-28 -left-20 w-80 h-80 rounded-full
                    bg-blue-600/10 blur-[90px] animate-pulse"></div>
        <div class="absolute -bottom-16 right-0 w-64 h-64 rounded-full
                    bg-indigo-500/8 blur-[80px]
                    animate-[pulse_7s_ease-in-out_infinite_alternate]"></div>
        {{-- Grid --}}
        <div class="absolute inset-0 opacity-[0.022]"
             style="background:linear-gradient(rgba(59,130,246,.7) 1px,transparent 1px),
                    linear-gradient(90deg,rgba(59,130,246,.7) 1px,transparent 1px);
                    background-size:36px 36px"></div>
    </div>

    {{-- ── Logo ────────────────────────────────────────── --}}
    <div class="relative z-10 px-6 pt-7 pb-5 flex-shrink-0">
        <div class="flex items-center gap-3.5">
            <div class="logo-pulse relative flex-shrink-0 w-11 h-11 rounded-[14px]
                        bg-gradient-to-br from-blue-500 to-blue-700
                        flex items-center justify-center">
                <i class="fa-solid fa-vault text-white text-[17px] leading-none"></i>
                <div class="absolute inset-0 rounded-[14px]
                            bg-gradient-to-br from-white/18 to-transparent"></div>
            </div>
            <div>
                <h1 class="text-[21px] font-black tracking-tight text-white leading-none">
                    Indonesia National<span class="text-blue-400">Bank</span>
                </h1>
                <p class="text-[9px] font-extrabold text-gray-500 uppercase tracking-[0.22em] mt-1">
                    Digital Ecosystem
                </p>
            </div>
        </div>

        <div class="mt-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-[6px]
                         bg-blue-500/10 border border-blue-500/20 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                <span class="text-[10px] font-extrabold text-blue-400 uppercase tracking-widest">
                    Premium Member
                </span>
            </span>
        </div>
    </div>

    {{-- ── Navigation ───────────────────────────────────── --}}
    <nav id="sidebarNav"
         class="relative z-10 flex-1 overflow-y-auto overflow-x-hidden px-3 pb-3 space-y-0.5"
         style="scrollbar-width:thin;scrollbar-color:rgba(255,255,255,0.05) transparent">

        {{-- ─── Main ─────────────────────────────────────── --}}
        <p class="{{ $lbl }}">Menu Utama</p>

        <a href="{{ route('dashboard') }}"
           class="{{ $navBase }} {{ $r('dashboard') ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r('dashboard') ? $iOn : $iOff }}">
                <i class="fa-solid fa-grid-2"></i>
            </span>
            <span class="flex-1 leading-none">Ringkasan Akun</span>
            @if($r('dashboard'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        <a href="{{ route('transfers.create') }}"
           class="{{ $navBase }} {{ $r('transfers.*') ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r('transfers.*') ? $iOn : $iOff }}">
                <i class="fa-solid fa-paper-plane"></i>
            </span>
            <span class="flex-1 leading-none">Transfer Dana</span>
            @if($r('transfers.*'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        <a href="{{ route('transactions.index') }}"
           class="{{ $navBase }} {{ $r('transactions.*') ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r('transactions.*') ? $iOn : $iOff }}">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </span>
            <span class="flex-1 leading-none">Riwayat Transaksi</span>
            @if($r('transactions.*'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        {{-- ─── Layanan ──────────────────────────────────── --}}
        <p class="{{ $lbl }}">Layanan &amp; Fitur</p>

        <a href="{{ route('top_ups.create') }}"
           class="{{ $navBase }} {{ $r('top_ups.*') ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r('top_ups.*') ? $iOn : $iOff }}">
                <i class="fa-solid fa-wallet"></i>
            </span>
            <span class="flex-1 leading-none">Top Up Saldo</span>
            @if($r('top_ups.*'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        <a href="{{ route('bills.index') }}"
           class="{{ $navBase }} {{ $r(['bills.*','bill_payments.*']) ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r(['bills.*','bill_payments.*']) ? $iOn : $iOff }}">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </span>
            <span class="flex-1 leading-none">Bayar Tagihan</span>
            @if($r(['bills.*','bill_payments.*']))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        <a href="{{ route('loans.index') }}"
           class="{{ $navBase }} {{ $r('loans.*') ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r('loans.*') ? $iOn : $iOff }}">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </span>
            <span class="flex-1 leading-none">Pinjaman Aman</span>
            @if($r('loans.*'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        <a href="{{ route('savings_books.index') }}"
           class="{{ $navBase }} {{ $r('savings_books.*') ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r('savings_books.*') ? $iOn : $iOff }}">
                <i class="fa-solid fa-book-open"></i>
            </span>
            <span class="flex-1 leading-none">Buku Tabungan</span>
            @if($r('savings_books.*'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        {{-- ─── Akun ─────────────────────────────────────── --}}
        <p class="{{ $lbl }}">Akun Saya</p>

        <a href="{{ route('accounts.index') }}"
           class="{{ $navBase }} {{ $r('accounts.*') ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r('accounts.*') ? $iOn : $iOff }}">
                <i class="fa-solid fa-credit-card"></i>
            </span>
            <span class="flex-1 leading-none">Rekening Saya</span>
            @if($r('accounts.*'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        <a href="{{ route('profile.show') }}"
           class="{{ $navBase }} {{ $r('profile.*') ? $active : $inactive }}" data-nav>
            <span class="{{ $iconBase }} {{ $r('profile.*') ? $iOn : $iOff }}">
                <i class="fa-solid fa-circle-user"></i>
            </span>
            <span class="flex-1 leading-none">Profil Saya</span>
            @if($r('profile.*'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
            <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
        </a>

        {{-- ─── Admin ────────────────────────────────────── --}}
        @if(auth()->user()->role === 'admin')
            <p class="{{ $lbl }}">Administrasi</p>

            <a href="{{ route('admin.dashboard') }}"
               class="{{ $navBase }} {{ $r('admin.*') ? $active : $inactive }}" data-nav>
                <span class="{{ $iconBase }} {{ $r('admin.*') ? $iOn : $iOff }}">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
                <span class="flex-1 leading-none">Panel Admin</span>
                @if($r('admin.*'))<i class="fa-solid fa-chevron-right text-[9px] text-blue-400"></i>@endif
                <span class="rpl absolute inset-0 overflow-hidden rounded-2xl pointer-events-none"></span>
            </a>
        @endif

        <div class="h-3"></div>
    </nav>

    {{-- ── Bottom ───────────────────────────────────────── --}}
    <div class="relative z-10 p-3 flex-shrink-0 space-y-2.5">

        {{-- Help card --}}
        <div class="relative overflow-hidden rounded-[18px]
                    bg-gradient-to-br from-blue-500/10 to-indigo-600/8
                    border border-blue-500/20 p-4">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full
                        bg-blue-400/15 blur-2xl pointer-events-none"></div>
            <div class="relative flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-500/20
                            flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-headset text-blue-400 text-xs"></i>
                </div>
                <div>
                    <p class="text-[11px] font-extrabold text-blue-400 mb-0.5">Butuh Bantuan?</p>
                    <p class="text-[10px] text-gray-500 leading-relaxed mb-2">Layanan CS 24/7 siap membantu.</p>
                    <a href="#"
                       class="inline-flex items-center gap-1 text-[10px] font-extrabold
                              text-white/75 hover:text-blue-400 uppercase tracking-widest
                              transition-colors group">
                        Hubungi Kami
                        <i class="fa-solid fa-arrow-right text-[8px]
                                  group-hover:translate-x-1 transition-transform duration-200"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2.5 py-3 rounded-[14px]
                           bg-red-500/8 border border-red-500/15 text-red-400
                           text-[11px] font-extrabold uppercase tracking-[0.15em]
                           transition-all duration-300 cursor-pointer
                           hover:bg-red-500 hover:text-white hover:border-red-500
                           hover:shadow-[0_0_24px_rgba(239,68,68,0.32)]
                           hover:scale-[1.02] active:scale-95">
                <i class="fa-solid fa-power-off text-xs"></i>
                Keluar Sesi
            </button>
        </form>
    </div>

</aside>

{{-- ── Inline keyframes Tailwind can't generate ── --}}
<style>
    .logo-pulse {
        box-shadow: 0 0 24px rgba(59,130,246,.48), 0 4px 16px rgba(37,99,235,.4);
        animation: logoPulse 3s ease-in-out infinite;
    }
    @keyframes logoPulse {
        0%,100% { box-shadow: 0 0 22px rgba(59,130,246,.48), 0 4px 14px rgba(37,99,235,.4); }
        50%      { box-shadow: 0 0 38px rgba(59,130,246,.7), 0 4px 22px rgba(37,99,235,.6); }
    }
    @keyframes rippleOut {
        to { transform: scale(4.5); opacity: 0; }
    }
    .rpl-dot {
        position: absolute;
        border-radius: 50%;
        background: rgba(59,130,246,.2);
        transform: scale(0);
        animation: rippleOut .55s linear forwards;
        pointer-events: none;
    }
</style>

<script>
(function(){
    /* Ripple on click */
    document.querySelectorAll('[data-nav]').forEach(link => {
        link.addEventListener('click', function(e){
            const container = this.querySelector('.rpl');
            if(!container) return;
            const rect = this.getBoundingClientRect();
            const sz   = Math.max(rect.width, rect.height);
            const dot  = document.createElement('span');
            dot.className = 'rpl-dot';
            dot.style.cssText = `width:${sz}px;height:${sz}px;left:${e.clientX-rect.left-sz/2}px;top:${e.clientY-rect.top-sz/2}px`;
            container.appendChild(dot);
            setTimeout(()=>dot.remove(), 600);
        });
    });

    /* Staggered entrance */
    document.querySelectorAll('[data-nav]').forEach((el, i) => {
        el.style.opacity   = '0';
        el.style.transform = 'translateX(-12px)';
        el.style.transition = `opacity .38s ease ${50+i*36}ms,transform .38s cubic-bezier(.34,1.2,.64,1) ${50+i*36}ms`;
        requestAnimationFrame(() => { el.style.opacity=''; el.style.transform=''; });
    });

    /* Scroll active into view */
    const active = document.querySelector('[data-nav].bg-gradient-to-r');
    active?.scrollIntoView({ block:'nearest', behavior:'smooth' });
})();
</script>
