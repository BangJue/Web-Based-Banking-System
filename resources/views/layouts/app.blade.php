<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | INB Premium</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
            color: #1a1a1a;
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #000000 100%);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .active-link {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0) 100%);
            border-left: 4px solid #3b82f6;
            color: #ffffff !important;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        /* Sidebar mobile transition */
        #sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sidebar.sidebar-hidden {
            transform: translateX(-100%);
        }
        #sidebar-overlay {
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">

<div class="flex min-h-screen">

    {{-- =========================================================
         OVERLAY (mobile) — klik untuk tutup sidebar
    ========================================================= --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/60 z-40 hidden opacity-0"
         onclick="closeSidebar()">
    </div>

    {{-- =========================================================
         SIDEBAR
    ========================================================= --}}
    <aside id="sidebar"
           class="w-72 sidebar-gradient text-white flex flex-col
                  fixed lg:sticky top-0 h-screen z-50 shadow-2xl
                  sidebar-hidden lg:translate-x-0 lg:sidebar-hidden-none">

        {{-- Logo --}}
        <div class="p-7 mb-2 flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                        <i class="fa-solid fa-vault text-white text-base"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-black tracking-tight leading-tight uppercase">
                            Indonesia <span class="text-blue-500">National</span>
                        </h1>
                        <p class="text-[10px] text-gray-400 font-bold tracking-widest -mt-0.5">BANK</p>
                    </div>
                </div>
                <div class="mt-3 inline-block px-3 py-1 bg-white/5 border border-white/10 rounded-full">
                    <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest">Premium Member</span>
                </div>
            </div>
            {{-- Close button (mobile only) --}}
            <button onclick="closeSidebar()"
                    class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 transition-colors text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 space-y-0.5 custom-scrollbar overflow-y-auto pb-4">

            <p class="px-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 mt-2">Menu Utama</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Ringkasan Akun</span>
            </a>

            <a href="{{ route('transfers.create') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('transfers.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-paper-plane w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Transfer Dana</span>
            </a>

            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('transactions.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Riwayat Transaksi</span>
            </a>

            <p class="px-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mt-6 mb-3">Layanan & Fitur</p>

            <a href="{{ route('top_ups.create') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('top_ups.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-circle-plus w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Top Up Saldo</span>
            </a>

            <a href="{{ route('loans.index') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('loans.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-hand-holding-dollar w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Pinjaman Aman</span>
            </a>

            <a href="{{ route('bills.index') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('bills.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Bayar Tagihan</span>
            </a>

            <a href="{{ route('savings_books.index') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('savings_books.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-book-open w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Buku Tabungan</span>
            </a>

        </nav>

        {{-- Footer sidebar --}}
        <div class="p-5">
            <div class="bg-blue-600/10 border border-blue-500/20 p-5 rounded-2xl mb-4">
                <p class="text-xs text-blue-400 font-bold mb-1">Butuh Bantuan?</p>
                <p class="text-[10px] text-gray-400 mb-3 leading-relaxed">Layanan CS 24/7 selalu siap membantu Anda.</p>
                <a href="#" class="text-[10px] font-black text-white hover:underline uppercase tracking-widest flex items-center gap-1.5">
                    <i class="fa-solid fa-headset text-blue-400"></i> Hubungi Kami
                </a>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2.5 p-3.5 bg-red-500/10 text-red-400 font-black rounded-2xl hover:bg-red-500 hover:text-white transition-all duration-300 uppercase text-[11px] tracking-widest">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Keluar Sesi</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- =========================================================
         MAIN CONTENT
    ========================================================= --}}
    <main class="flex-1 flex flex-col min-w-0">

        {{-- Topbar --}}
        <header class="h-20 glass-nav border-b border-gray-200/50 flex items-center justify-between px-6 md:px-10 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                {{-- Hamburger (mobile + desktop toggle) --}}
                <button id="sidebar-toggle"
                        onclick="toggleSidebar()"
                        class="w-11 h-11 flex items-center justify-center bg-black text-white rounded-xl shadow-lg hover:bg-blue-600 transition-colors">
                    <i class="fa-solid fa-bars-staggered text-base"></i>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-lg font-black text-black leading-none">INB <span class="text-blue-600">Portal</span></h2>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mt-0.5">Premium Interface v2.0</p>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-5">
                {{-- Notif --}}
                <button class="w-11 h-11 rounded-2xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-white hover:border-blue-500 hover:text-blue-600 transition-all relative">
                    <i class="fa-solid fa-bell text-base"></i>
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                {{-- Profile --}}
                <div class="flex items-center gap-3 bg-white p-1.5 pr-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000&color=fff"
                             class="w-full h-full object-cover rounded-[9px]" alt="Profile">
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-black text-black leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-black text-blue-600 uppercase mt-0.5 tracking-widest italic">{{ auth()->user()->role }} Account</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <div id="page-content" class="p-6 md:p-10 opacity-0 translate-y-4 transition-all duration-500">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>

        {{-- Footer --}}
        <footer class="mt-auto py-6 px-6 md:px-10 border-t border-gray-200/50">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">&copy; 2026 INB Digital Ecosystem.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-[10px] font-black text-gray-500 hover:text-blue-600 uppercase tracking-widest transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="text-[10px] font-black text-gray-500 hover:text-blue-600 uppercase tracking-widest transition-colors">Privasi</a>
                </div>
            </div>
        </footer>

    </main>
</div>

{{-- =========================================================
     SIDEBAR JS — open/close + responsive
========================================================= --}}
<script>
    const sidebar       = document.getElementById('sidebar');
    const overlay       = document.getElementById('sidebar-overlay');
    const pageContent   = document.getElementById('page-content');
    const isLg          = () => window.innerWidth >= 1024;

    // Inisialisasi state sidebar
    // Desktop: default OPEN; Mobile: default CLOSED
    let sidebarOpen = isLg();

    function applyState() {
        if (sidebarOpen) {
            sidebar.classList.remove('sidebar-hidden');
            if (!isLg()) {
                // Mobile: tampilkan overlay
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.replace('opacity-0', 'opacity-100'), 10);
                document.body.classList.add('overflow-hidden');
            } else {
                overlay.classList.add('hidden');
                overlay.classList.replace('opacity-100', 'opacity-0');
                document.body.classList.remove('overflow-hidden');
            }
        } else {
            sidebar.classList.add('sidebar-hidden');
            overlay.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
            document.body.classList.remove('overflow-hidden');
        }
    }

    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
        applyState();
    }

    function closeSidebar() {
        sidebarOpen = false;
        applyState();
    }

    // Re-check on resize
    window.addEventListener('resize', () => {
        if (isLg()) {
            // Desktop: selalu paksa sidebar tampak, hapus overlay
            sidebarOpen = true;
        }
        applyState();
    });

    // Inisialisasi state pertama
    applyState();

    // Fade-in page content
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => {
            pageContent.classList.remove('opacity-0', 'translate-y-4');
            pageContent.classList.add('opacity-100', 'translate-y-0');
        }, 80);
    });
</script>

@stack('scripts')
</body>
</html>