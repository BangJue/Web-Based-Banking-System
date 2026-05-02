<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | INB Core Systems</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .sidebar-admin { background: #020617; }
        .glass-nav { background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .active-link-admin {
            background: linear-gradient(90deg, rgba(99,102,241,0.2) 0%, rgba(99,102,241,0) 100%);
            border-left: 4px solid #6366f1;
            color: #ffffff !important;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        #admin-sidebar { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        #admin-sidebar.sidebar-hidden { transform: translateX(-100%); }
        #admin-overlay { transition: opacity 0.3s ease; }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
<div class="flex min-h-screen">

    {{-- Overlay mobile --}}
    <div id="admin-overlay" class="fixed inset-0 bg-black/60 z-40 hidden opacity-0" onclick="closeSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="admin-sidebar"
           class="w-72 sidebar-admin text-white flex flex-col fixed lg:sticky top-0 h-screen z-50 shadow-2xl sidebar-hidden lg:translate-x-0">

        {{-- Logo --}}
        <div class="p-7 mb-2 flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/30">
                        <i class="fa-solid fa-shield-halved text-white text-base"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-black tracking-tight leading-tight uppercase">INB <span class="text-indigo-400">Admin</span></h1>
                        <p class="text-[9px] font-bold text-gray-600 tracking-widest">CENTRAL CONTROL</p>
                    </div>
                </div>
                <div class="mt-3 inline-block px-3 py-1 bg-white/5 border border-white/10 rounded-full">
                    <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">Super Administrator</span>
                </div>
            </div>
            <button onclick="closeSidebar()" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 transition-colors text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 space-y-0.5 custom-scrollbar overflow-y-auto pb-4">

            <p class="px-4 text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] mb-3 mt-2">Pengawasan System</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-chart-line w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Dashboard Admin</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-users-gear w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Kelola Nasabah</span>
            </a>

            <a href="{{ route('admin.accounts.index') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.accounts.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-landmark w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Daftar Rekening</span>
            </a>

            <p class="px-4 text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] mt-6 mb-3">Operasional</p>

            <a href="{{ route('admin.loans.index') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.loans.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-stamp w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Persetujuan Kredit</span>
            </a>

            <a href="{{ route('admin.bills.index') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.bills.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Layanan Tagihan</span>
            </a>

            <p class="px-4 text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] mt-6 mb-3">Akun Saya</p>

            <a href="{{ route('admin.profile.show') }}"
               class="flex items-center gap-4 p-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.profile.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-user-shield w-5 text-center text-base"></i>
                <span class="font-bold text-sm">Profil Admin</span>
            </a>

        </nav>

        {{-- Footer --}}
        <div class="p-5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2.5 p-3.5 bg-red-500/10 text-red-400 font-black rounded-2xl hover:bg-red-500 hover:text-white transition-all duration-300 uppercase text-[11px] tracking-widest">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>End Admin Session</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 flex flex-col min-w-0">

        {{-- Topbar --}}
        <header class="h-20 glass-nav border-b border-gray-200 flex items-center justify-between px-6 md:px-10 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()"
                    class="w-11 h-11 flex items-center justify-center bg-slate-900 text-white rounded-xl shadow-lg hover:bg-indigo-600 transition-colors">
                    <i class="fa-solid fa-bars-staggered text-base"></i>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-lg font-black text-gray-900 leading-none">System <span class="text-indigo-600">Console</span></h2>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Operational · Normal</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-5">
                <div class="hidden md:block text-right border-r border-gray-100 pr-4 mr-1">
                    <p id="live-clock" class="text-sm font-black text-gray-900 leading-none font-mono"></p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">Server Time (UTC+7)</p>
                </div>
                <div class="flex items-center gap-3 bg-white p-1.5 pr-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-user-gear text-sm"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-black text-black leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-black text-indigo-600 uppercase mt-0.5 tracking-widest italic">Super Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <div id="admin-content" class="p-6 md:p-10 opacity-0 translate-y-4 transition-all duration-500">
            <div class="max-w-7xl mx-auto">

                @if(session('success'))
                <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-3 text-indigo-700 font-bold text-sm">
                    <i class="fa-solid fa-circle-check text-indigo-500"></i> {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-700 font-bold text-sm">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
        </div>

        <footer class="mt-auto py-6 px-6 md:px-10 border-t border-gray-100">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">&copy; 2026 INB Core Systems &middot; Admin Panel</p>
                <p class="text-[10px] text-gray-400 font-mono">v2.0.0</p>
            </div>
        </footer>
    </main>
</div>

<script>
    const adminSidebar  = document.getElementById('admin-sidebar');
    const adminOverlay  = document.getElementById('admin-overlay');
    const adminContent  = document.getElementById('admin-content');
    const isLg = () => window.innerWidth >= 1024;
    let sidebarOpen = isLg();

    function applyState() {
        if (sidebarOpen) {
            adminSidebar.classList.remove('sidebar-hidden');
            if (!isLg()) {
                adminOverlay.classList.remove('hidden');
                setTimeout(() => adminOverlay.classList.replace('opacity-0','opacity-100'), 10);
                document.body.classList.add('overflow-hidden');
            } else {
                adminOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        } else {
            adminSidebar.classList.add('sidebar-hidden');
            adminOverlay.classList.replace('opacity-100','opacity-0');
            setTimeout(() => adminOverlay.classList.add('hidden'), 300);
            document.body.classList.remove('overflow-hidden');
        }
    }
    function toggleSidebar() { sidebarOpen = !sidebarOpen; applyState(); }
    function closeSidebar()  { sidebarOpen = false; applyState(); }
    window.addEventListener('resize', () => { if (isLg()) sidebarOpen = true; applyState(); });
    applyState();

    // Fade-in
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            adminContent.classList.remove('opacity-0','translate-y-4');
            adminContent.classList.add('opacity-100','translate-y-0');
        }, 80);
    });

    // Live clock
    function updateClock() {
        const el = document.getElementById('live-clock');
        if (el) el.textContent = new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@stack('scripts')
</body>
</html>