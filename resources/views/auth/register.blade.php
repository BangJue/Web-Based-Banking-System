<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | INB Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }
        
        /* Outline tambahan untuk kontras input */
        .input-outline {
            border: 2px solid #e5e7eb !important; /* Border abu-abu lebih tebal */
        }
        .input-outline:focus {
            border-color: #2563eb !important; /* Border biru saat fokus */
        }
    </style>
</head>
<body class="bg-slate-50"> <!-- Ganti bg-white ke slate-50 sedikit agar form putih terlihat pop-out -->

    <div class="flex min-h-screen">

        <!-- LEFT SIDE -->
        <div class="hidden lg:flex lg:w-1/2 bg-black relative flex-col justify-between p-12 overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-80 h-80 bg-blue-600 rounded-full blur-[120px] opacity-30"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 text-white mb-12">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/50">
                        <i class="fa-solid fa-building-columns text-2xl"></i>
                    </div>
                    <span class="font-bold text-2xl tracking-wider uppercase">
                        Indonesia National <span class="text-blue-500">Bank</span>
                    </span>
                </div>

                <h1 class="text-5xl font-black text-white leading-tight">
                    Join the <br><span class="text-blue-500">Elite</span> Circle.
                </h1>

                <p class="text-gray-400 mt-6 text-lg max-w-sm">
                    Daftar sekarang dan nikmati kendali penuh atas finansial Anda dengan teknologi enkripsi tercanggih.
                </p>
            </div>

            <div class="relative z-10 p-8 rounded-[2.5rem] border border-white/10 glass max-w-sm">
                <div class="flex -space-x-3 mb-4">
                    <img class="w-10 h-10 rounded-full border-2 border-black" src="https://ui-avatars.com/api/?name=John+Doe&bg=2563eb&color=fff">
                    <img class="w-10 h-10 rounded-full border-2 border-black" src="https://ui-avatars.com/api/?name=Jane+Smith&bg=10b981&color=fff">
                    <img class="w-10 h-10 rounded-full border-2 border-black" src="https://ui-avatars.com/api/?name=Mike+Ross&bg=f59e0b&color=fff">
                    <div class="w-10 h-10 rounded-full border-2 border-black bg-gray-800 flex items-center justify-center text-[10px] text-white font-bold">
                        +2k
                    </div>
                </div>
                <p class="text-white italic text-sm">
                    "Sistem perbankan tercepat yang pernah saya gunakan."
                </p>
                <p class="text-blue-500 text-xs mt-2 font-bold">
                    — Nasabah Terverifikasi
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="w-full lg:w-1/2 flex items-start justify-center p-6 md:p-12 overflow-y-auto relative">
            <!-- Aksen Background Halus -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -z-10 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-gray-100 rounded-full blur-3xl -z-10 opacity-50"></div>
            
            <div class="w-full max-w-xl space-y-8 py-12 relative z-10">

                <!-- HEADER -->
                <div>
                    <h2 class="text-4xl font-black text-black tracking-tight">Create Account</h2>
                    <p class="text-gray-500 mt-2 font-medium">
                        Lengkapi data diri Anda untuk membuka rekening Indonesia National Bank.
                    </p>
                </div>

                <!-- ERROR -->
                @if ($errors->any())
                    <div class="bg-red-50 border-2 border-red-200 p-4 rounded-xl">
                        <p class="text-red-700 text-sm font-bold">{{ $errors->first() }}</p>
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ route('register') }}" 
                      class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10 bg-white p-8 rounded-3xl border border-gray-200 shadow-xl shadow-gray-200/50">
                    @csrf
                    
                    <!-- NAMA -->
                    <div class="space-y-2 md:col-span-2 block">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" required
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-medium"
                            placeholder="Contoh: Budi Santoso">
                    </div>

                    <!-- EMAIL -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Email</label>
                        <input type="email" name="email" required
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-medium">
                    </div>

                    <!-- PHONE -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Nomor Telepon</label>
                        <input type="text" name="phone" required
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-medium">
                    </div>

                    <!-- BIRTH DATE -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" required
                            max="{{ date('Y-m-d', strtotime('-17 years')) }}"
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-medium">
                    </div>

                    <!-- GENDER -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Gender</label>
                        <select name="gender" required
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all">
                            <option value="">Pilih</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>

                    <!-- CITY -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Kota</label>
                        <input type="text" name="city" required
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <!-- NIK -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">NIK</label>
                        <input type="text" name="nik" required maxlength="16"
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <!-- ADDRESS -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Alamat</label>
                        <textarea name="address" required rows="3"
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all"></textarea>
                    </div>

                    <!-- PASSWORD -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <!-- CONFIRM -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Konfirmasi</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <!-- PIN -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">PIN</label>
                        <input type="password" name="pin" required maxlength="6"
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <!-- PIN CONFIRM -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-1">Konfirmasi PIN</label>
                        <input type="password" name="pin_confirmation" required maxlength="6"
                            class="w-full px-5 py-4 bg-white input-outline rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all">
                    </div>

                    <!-- BUTTON -->
                    <div class="md:col-span-2 pt-4">
                        <button type="submit"
                            class="w-full bg-black text-white py-4 rounded-2xl font-bold hover:bg-blue-600 shadow-lg hover:shadow-blue-500/30 transition-all transform active:scale-[0.98]">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="text-gray-500 text-sm">Sudah punya akun? <a href="/login" class="text-blue-600 font-bold hover:underline">Masuk di sini</a></p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>