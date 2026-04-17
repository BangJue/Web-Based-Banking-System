<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | NexusBank</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8FAFC] flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] p-10 shadow-2xl shadow-blue-500/10 text-center border border-gray-100">
        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-8">
            <i class="fa-solid fa-paper-plane text-3xl text-blue-600 animate-pulse"></i>
        </div>

        <h2 class="text-3xl font-black text-black mb-4">Cek Email Anda</h2>
        
        <p class="text-gray-500 font-medium mb-4 leading-relaxed">
            Link verifikasi telah dikirim ke: <br>
            <span class="text-black font-bold">{{ auth()->user()->email }}</span>
        </p>

        <p class="text-gray-500 text-sm mb-8">
            Silakan klik link di dalam email tersebut untuk mengaktifkan akun NexusBank Anda.
        </p>

        @if (session('message'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-2xl text-[13px] font-bold border border-green-100 flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                Link baru berhasil dikirim ulang!
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold hover:bg-blue-600 transition-all duration-300 shadow-lg active:scale-95">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            {{-- <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 font-bold text-sm hover:text-red-500 transition-colors uppercase tracking-widest">
                    Logout & Gunakan Email Lain
                </button>
            </form> --}}
        </div>
    </div>

</body>
</html>