<nav class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-4 flex items-center justify-between">
    <div>
        <h2 class="text-black font-bold text-2xl tracking-tight">
            @yield('title', 'Welcome Back!')
        </h2>
        <p class="text-gray-400 text-sm font-medium">Hello, {{ Auth::user()->name }} 👋</p>
    </div>

    <div class="flex items-center gap-6">
        <div class="hidden md:flex flex-col items-end mr-4">
            <span id="real-time-clock" class="text-black font-bold tracking-widest">00:00:00</span>
            <span class="text-[10px] text-blue-600 uppercase font-bold tracking-widest">Real-time Server</span>
        </div>

        <div class="relative group" x-data="{ open: false }">
            <button @click="open = !open" class="w-11 h-11 bg-gray-50 rounded-full flex items-center justify-center text-black hover:bg-blue-600 hover:text-white transition-all duration-300">
                <i class="fa-regular fa-bell text-xl"></i>
                <span class="absolute top-2 right-2 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></span>
            </button>
            
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-3 w-80 bg-white shadow-2xl rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-4 bg-black text-white flex justify-between">
                    <span class="font-bold">Notifications</span>
                    <span class="text-xs bg-blue-600 px-2 py-1 rounded">2 New</span>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <div class="p-4 border-b border-gray-50 hover:bg-blue-50 transition-colors cursor-pointer">
                        <p class="text-sm font-bold text-black">Transfer Success!</p>
                        <p class="text-xs text-gray-500">You sent IDR 500,000 to John Doe</p>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 bg-black text-white py-2 px-4 rounded-full hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/20">
            <span class="font-semibold text-sm">Settings</span>
            <i class="fa-solid fa-gear animate-spin-slow"></i>
        </a>
    </div>
</nav>

<script>
    function updateClock() {
        const now = new Date();
        document.getElementById('real-time-clock').innerText = now.toLocaleTimeString('id-ID');
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>