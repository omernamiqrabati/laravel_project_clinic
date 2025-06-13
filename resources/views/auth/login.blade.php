<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dental Clinic Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #e0f7ff 0%, #b3e5fc 25%, #81d4fa 50%, #4fc3f7 75%, #29b6f6 100%);
            min-height: 100vh;
        }
        .dental-pattern {
            background-image: 
                radial-gradient(circle at 20px 20px, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 60px 60px, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 80px 80px, 40px 40px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        }
        .tooth-shine {
            animation: toothShine 3s ease-in-out infinite;
        }
        @keyframes toothShine {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(1deg); }
            66% { transform: translateY(5px) rotate(-0.5deg); }
        }
        .input-focus {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }
        .btn-dental {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-dental:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 50%, #075985 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(14, 165, 233, 0.4);
        }
    </style>
</head>
<body class="dental-pattern">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Background decorative elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl floating"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-blue-200/20 rounded-full blur-3xl floating" style="animation-delay: -2s;"></div>
            <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-cyan-100/30 rounded-full blur-xl floating" style="animation-delay: -4s;"></div>
        </div>

        <div class="max-w-md w-full space-y-8 relative z-10">
            <!-- Header Section -->
            <div class="text-center">
                <!-- Enhanced Dental Logo -->
                <div class="mx-auto h-20 w-20 bg-gradient-to-br from-blue-500 via-sky-500 to-cyan-500 rounded-3xl flex items-center justify-center shadow-2xl tooth-shine relative overflow-hidden">
                    <!-- Dental Tooth Icon -->
                    <svg class="h-12 w-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 1.11.25 2.16.7 3.1.45.94 1.05 1.77 1.8 2.4.75.63 1.6 1.1 2.5 1.35.9.25 1.85.25 2.75 0 .9-.25 1.75-.72 2.5-1.35.75-.63 1.35-1.46 1.8-2.4.45-.94.7-1.99.7-3.1 0-3.87-3.13-7-7-7zm0 2c2.76 0 5 2.24 5 5 0 .83-.19 1.62-.53 2.31-.34.69-.8 1.29-1.37 1.77-.57.48-1.22.83-1.9 1.02-.68.19-1.4.19-2.08 0-.68-.19-1.33-.54-1.9-1.02-.57-.48-1.03-1.08-1.37-1.77C7.19 10.62 7 9.83 7 9c0-2.76 2.24-5 5-5z"/>
                        <circle cx="12" cy="9" r="2" fill="white" opacity="0.8"/>
                    </svg>
                    <!-- Shine effect -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent transform -skew-x-12 translate-x-full animate-pulse"></div>
                </div>
                
                <!-- Enhanced Clinic Branding -->
                <div class="mt-8">
                    <h2 class="mt-2 text-xl font-semibold text-blue-700">
                        Professional Clinic Management
                    </h2>
                </div>
            </div>

            <!-- Enhanced Login Form Card -->
            <div class="glass-card py-8 px-8 rounded-3xl">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center" role="alert">
                        <svg class="w-5 h-5 mr-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error Message -->
                @if($errors->has('credentials'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center" role="alert">
                        <svg class="w-5 h-5 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ $errors->first('credentials') }}</span>
                    </div>
                @endif

                <!-- Login Form Header -->
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-800">Welcome Back</h3>
                    <p class="text-gray-600 mt-2 font-medium">Please sign in to access your clinic dashboard</p>
                    <div class="mt-4 w-16 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mx-auto"></div>
                </div>

                <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-semibold text-gray-700">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Email Address
                            </div>
                        </label>
                        <div class="relative">
                            <input id="username" name="username" type="email" required 
                                   class="input-focus appearance-none rounded-xl relative block w-full px-4 py-4 border-2 border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('username') border-red-400 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="Enter your email address"
                                   value="{{ old('username') }}">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('username')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Password
                            </div>
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required 
                                   class="input-focus appearance-none rounded-xl relative block w-full px-4 py-4 pr-12 border-2 border-gray-200 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('password') border-red-400 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="Enter your password">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-blue-600 transition-colors duration-200">
                                <!-- Eye Open Icon (default - password hidden) -->
                                <svg id="eyeOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <!-- Eye Closed Icon (hidden by default - password visible) -->
                                <svg id="eyeClosed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Enhanced Login Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="btn-dental group relative w-full flex justify-center py-4 px-6 border border-transparent text-base font-semibold rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <svg class="h-6 w-6 text-blue-200 group-hover:text-white transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </span>
                            <span class="flex items-center font-semibold">
                                Sign In to Dashboard
                                <svg class="ml-3 h-5 w-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Enhanced Forgot Password Link -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('password.request') }}" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-semibold transition duration-200 hover:underline group">
                            <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Forgot your password?
                        </a>
                    </div>
                </form>

                <!-- Enhanced Footer -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="text-center space-y-3">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Password Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            togglePassword.addEventListener('click', function() {
                // Toggle password visibility
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                }
            });

            // Add subtle animations on input focus
            const inputs = document.querySelectorAll('input[type="email"], input[type="password"], input[type="text"]');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html> 