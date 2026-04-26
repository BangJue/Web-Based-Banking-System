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
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">

    <div class="flex min-h-screen">
        <aside class="w-80 sidebar-gradient text-white hidden lg:flex flex-col sticky top-0 h-screen z-50 shadow-2xl">
            <div class="p-8 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                        <i class="fa-solid fa-vault text-white"></i>
                    </div>
                    <h1 class="text-2xl font-black tracking-tighter uppercase">Indonesia National <span class="text-blue-500">Bank</span></h1>
                </div>
                <div class="mt-2 inline-block px-3 py-1 bg-white/5 border border-white/10 rounded-full">
                    <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Premium Member</span>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1 custom-scrollbar overflow-y-auto">
                <p class="px-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Menu Utama</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-grid-2 text-lg"></i>
                    <span class="font-bold">Ringkasan Akun</span>
                </a>

                <a href="{{ route('transfers.create') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('transfers.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-paper-plane text-lg"></i>
                    <span class="font-bold">Transfer Dana</span>
                </a>

                <a href="{{ route('transactions.index') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('transactions.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    <span class="font-bold">Riwayat Transaksi</span>
                </a>

                <p class="px-4 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mt-8 mb-4">Layanan & Fitur</p>

                <a href="{{ route('top_ups.create') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('top_ups.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-wallet text-lg"></i>
                    <span class="font-bold">Top Up Saldo</span>
                </a>

                <a href="{{ route('loans.index') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('loans.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-hand-holding-dollar text-lg"></i>
                    <span class="font-bold">Pinjaman Aman</span>
                </a>

                <a href="{{ route('bills.index') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('loans.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-receipt text-lg"></i>
                    <span class="font-bold">Bayar Tagihan</span>
                </a>

                <a href="{{ route('savings_books.index') }}" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-300 {{ request()->routeIs('loans.*') ? 'active-link' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-receipt text-lg"></i>
                    <span class="font-bold">Saving Books</span>
                </a>
            </nav>

            <div class="p-6">
                <div class="bg-blue-600/10 border border-blue-500/20 p-6 rounded-[2rem] mb-6">
                    <p class="text-xs text-blue-400 font-bold mb-1">Butuh Bantuan?</p>
                    <p class="text-[10px] text-gray-400 mb-3 leading-relaxed">Layanan CS 24/7 selalu siap membantu Anda.</p>
                    <a href="#" class="text-xs font-black text-white hover:underline uppercase tracking-widest">Hubungi Kami</a>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 p-4 bg-red-500/10 text-red-500 font-black rounded-2xl hover:bg-red-500 hover:text-white transition-all duration-300 uppercase text-xs tracking-widest">
                        <i class="fa-solid fa-power-off"></i>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0">
            <header class="h-24 glass-nav border-b border-gray-200/50 flex items-center justify-between px-10 sticky top-0 z-40">
                <div class="flex items-center gap-6">
                    <button class="lg:hidden w-12 h-12 flex items-center justify-center bg-black text-white rounded-xl shadow-lg">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-black text-black">INB <span class="text-blue-600">Portal</span></h2>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-0.5">Premium Interface v2.0</p>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <button class="w-12 h-12 rounded-2xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-white hover:border-blue-500 hover:text-blue-600 transition-all relative">
                        <i class="fa-solid fa-bell"></i>
                        <span class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <div class="flex items-center gap-4 bg-white p-1.5 pr-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000&color=fff" class="w-full h-full object-cover rounded-[10px]" alt="Profile">
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-black text-black leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-[9px] font-black text-blue-600 uppercase mt-1 tracking-widest italic">{{ auth()->user()->role }} Account</p>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-10">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>

            <footer class="mt-auto py-8 px-10 border-t border-gray-200/50">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">&copy; 2026 INB Digital Ecosystem.</p>
                    <div class="flex gap-6">
                        <a href="#" class="text-[10px] font-black text-gray-500 hover:text-blue-600 uppercase tracking-widest transition-colors">Syarat & Ketentuan</a>
                        <a href="#" class="text-[10px] font-black text-gray-500 hover:text-blue-600 uppercase tracking-widest transition-colors">Privasi</a>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Efek fade in untuk content
            const content = document.querySelector('main > div');
            content.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-4');
            setTimeout(() => {
                content.classList.remove('opacity-0', 'translate-y-4');
                content.classList.add('opacity-100', 'translate-y-0');
            }, 100);
        });
    </script>
</body>
</html> 