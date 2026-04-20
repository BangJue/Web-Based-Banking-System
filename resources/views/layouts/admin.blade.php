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
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
            color: #0f172a;
        }
        .sidebar-admin {
            background: #020617; /* Lebih gelap dari layout nasabah */
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .active-link-admin {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.2) 0%, rgba(79, 70, 229, 0) 100%);
            border-left: 4px solid #6366f1;
            color: #ffffff !important;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>
<body class="antialiased overflow-x-hidden">

    <div class="flex min-h-screen">
        <aside class="w-80 sidebar-admin text-white hidden lg:flex flex-col sticky top-0 h-screen z-50 shadow-2xl">
            <div class="p-8 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/30">
                        <i class="fa-solid fa-shield-halved text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tighter uppercase">INB <span class="text-indigo-500">Admin</span></h1>
                        <p class="text-[9px] font-bold text-gray-500 tracking-widest uppercase">Central Control</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1 custom-scrollbar overflow-y-auto">
                <p class="px-4 text-[10px] font-black text-gray-600 uppercase tracking-[0.2em] mb-4">Pengawasan System</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                    <span class="font-bold">Dashboard Admin</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-users-gear text-lg"></i>
                    <span class="font-bold">Kelola Nasabah</span>
                </a>

                <a href="{{ route('admin.accounts.index') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.accounts.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                    <span class="font-bold">Daftar Rekening</span>
                </a>

                <p class="px-4 text-[10px] font-black text-gray-600 uppercase tracking-[0.2em] mt-8 mb-4">Operasional</p>

                <a href="{{ route('admin.loans.index') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.loans.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-stamp text-lg"></i>
                    <span class="font-bold">Persetujuan Kredit</span>
                </a>

                <a href="{{ route('admin.bills.index') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.bills.*') ? 'active-link-admin' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-receipt text-lg"></i>
                    <span class="font-bold">Layanan Tagihan</span>
                </a>

                <p class="px-4 text-[10px] font-black text-gray-600 uppercase tracking-[0.2em] mt-8 mb-4">Akun Saya</p>
                <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-4 p-4 rounded-xl text-gray-500 hover:text-white hover:bg-white/5 transition-all">
                    <i class="fa-solid fa-user-shield text-lg"></i>
                    <span class="font-bold">Profil Admin</span>
                </a>
            </nav>

            <div class="p-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 p-4 bg-red-500/10 text-red-500 font-black rounded-2xl hover:bg-red-500 hover:text-white transition-all duration-300 uppercase text-xs tracking-widest">
                        <i class="fa-solid fa-power-off"></i>
                        <span>End Admin Session</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0">
            <header class="h-24 glass-nav border-b border-gray-200 flex items-center justify-between px-10 sticky top-0 z-40">
                <div class="flex items-center gap-6">
                    <div>
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">System Console</h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Operational Status: Normal</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right mr-4 border-r pr-4 border-gray-100">
                        <p id="live-clock" class="text-sm font-black text-gray-900 leading-none"></p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase mt-1">Server Time (UTC+7)</p>
                    </div>

                    <div class="flex items-center gap-4 bg-white p-1.5 pr-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg">
                            <i class="fa-solid fa-user-gear text-sm"></i>
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-black text-black leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-[9px] font-black text-indigo-600 uppercase mt-1 tracking-widest italic">Super Administrator</p>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-10">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                    <div class="mb-8 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-3 text-indigo-700 font-bold text-sm shadow-sm">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        // Jam Real-time untuk Server Admin
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('live-clock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('main > div');
            content.classList.add('transition-all', 'duration-500', 'opacity-0');
            setTimeout(() => {
                content.classList.remove('opacity-0');
                content.classList.add('opacity-100');
            }, 100);
        });
    </script>
</body>
</html>