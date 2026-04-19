<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | INB Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-20px); } 100% { transform: translateY(0px); } }
        .floating { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-white overflow-hidden">

    <div class="flex h-screen">
        <div class="hidden lg:flex lg:w-1/2 bg-black relative flex-col justify-between p-12 overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-80 h-80 bg-blue-600 rounded-full blur-[120px] opacity-30"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-900 rounded-full blur-[150px] opacity-20"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 text-white mb-12">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/50">
                        <i class="fa-solid fa-building-columns text-2xl"></i>
                    </div>
                    <span class="font-bold text-2xl tracking-wider uppercase">Indonesia National <span class="text-blue-500">Bank</span></span>
                </div>

                <div class="space-y-6 max-w-md">
                    <h1 class="text-6xl font-black text-white leading-tight">Secure <br><span class="text-blue-500">Digital</span> Banking.</h1>
                    <p class="text-gray-400 text-lg">Experience the next generation of financial management with our premium security and seamless interface.</p>
                </div>
            </div>

            <div class="relative z-10 glass border border-white/10 p-8 rounded-[2.5rem] flex items-center gap-6 floating max-w-sm">
                <div class="w-16 h-16 bg-blue-600/20 rounded-full flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-shield-check text-3xl"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold">End-to-End Encryption</h4>
                    <p class="text-gray-500 text-sm">Your data is secured with AES-256 military-grade security.</p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 relative">
            <div class="absolute top-8 left-8 lg:hidden flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                    <i class="fa-solid fa-building-columns text-sm"></i>
                </div>
                <span class="font-bold text-xl text-black">INB</span>
            </div>

            <div class="w-full max-w-md space-y-8" 
                 x-data="{ showPass: false }"
                 x-init="() => { $el.classList.add('animate-fade-in') }">
                
                <div>
                    <h2 class="text-4xl font-black text-black tracking-tight">Sign In</h2>
                    <p class="text-gray-500 mt-2 font-medium">Please enter your credentials to access your account.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 ml-1">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-blue-600 text-gray-400 transition-colors">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                            <input type="email" name="email" required autofocus
                                class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium"
                                placeholder="name@company.com">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center ml-1">
                            <label class="text-xs font-black uppercase tracking-widest text-gray-400">Password</label>
                            <a href="#" class="text-xs font-bold text-blue-600 hover:text-black transition-colors">Forgot Password?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-blue-600 text-gray-400 transition-colors">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input :type="showPass ? 'text' : 'password'" name="password" required
                                class="w-full pl-11 pr-12 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                                <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="remember" class="sr-only peer">
                            <div class="w-5 h-5 bg-gray-100 border border-gray-200 rounded-md peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all"></div>
                            <i class="fa-solid fa-check absolute top-1 left-1 text-[10px] text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-500 group-hover:text-black transition-colors">Keep me signed in</span>
                    </label>

                    <button type="submit" 
                        class="w-full bg-black text-white py-4 rounded-2xl font-bold text-lg hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/40 transition-all duration-500 transform hover:-translate-y-1">
                        Login Account
                    </button>
                </form>

                <div class="pt-6 text-center">
                    <p class="text-gray-500 font-medium">Don't have an account? 
                        <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline ml-1">Create Account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>