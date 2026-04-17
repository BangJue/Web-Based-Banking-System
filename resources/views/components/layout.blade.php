<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'INB - Indonesia National Bank' }}</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .gradient-blue { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-blue-100 selection:text-blue-600">
    
    <x-navbar />

    <main class="min-h-screen pt-20">
        {{ $slot }}
    </main>

    <footer class="bg-slate-950 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <h3 class="text-2xl font-bold mb-4">INB<span class="text-blue-500">.</span></h3>
                <p class="text-slate-400">Indonesia National Bank - Solusi Perbankan Masa Depan.</p>
            </div>
            <div class="flex space-x-8">
                <a href="#" class="text-slate-400 hover:text-white transition-colors">Keamanan</a>
                <a href="#" class="text-slate-400 hover:text-white transition-colors">Privasi</a>
            </div>
            <div class="text-right">
                <p class="text-slate-500 text-sm">© 2026 Indonesia National Bank.</p>
            </div>
        </div>
    </footer>

    <script>
        // Inisialisasi Lucide Icons
        lucide.createIcons();
    </script>
</body>
</html>