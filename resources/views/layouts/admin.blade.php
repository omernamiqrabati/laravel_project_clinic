<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dental Clinic Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .nav-link {
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            transform: translateX(4px);
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }
        .main-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 text-gray-800 h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="sidebar-gradient w-72 h-full shadow-2xl border-r border-slate-200/50 p-8 flex flex-col justify-between flex-shrink-0">
        <div>
            <!-- Logo Section -->
            <div class="text-center mb-12">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-2xl">🦷</span>
                </div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Dental Admin
                </h1>
                <p class="text-sm text-slate-500 mt-1">Management Portal</p>
            </div>

            <!-- Navigation -->
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-4 p-3 rounded-xl text-slate-700 hover:shadow-lg">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> 
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.patients.index') }}" class="nav-link flex items-center gap-4 p-3 rounded-xl text-slate-700 hover:shadow-lg">
                            <i data-lucide="users" class="w-5 h-5"></i> 
                            <span class="font-medium">Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.dentists.index') }}" class="nav-link flex items-center gap-4 p-3 rounded-xl text-slate-700 hover:shadow-lg">
                            <i data-lucide="user-check" class="w-5 h-5"></i> 
                            <span class="font-medium">Dentists</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.receptionists.index') }}" class="nav-link flex items-center gap-4 p-3 rounded-xl text-slate-700 hover:shadow-lg">
                            <i data-lucide="headphones" class="w-5 h-5"></i> 
                            <span class="font-medium">Receptionists</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.appointments.index') }}" class="nav-link flex items-center gap-4 p-3 rounded-xl text-slate-700 hover:shadow-lg">
                            <i data-lucide="calendar-clock" class="w-5 h-5"></i> 
                            <span class="font-medium">Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.treatments.index') }}" class="nav-link flex items-center gap-4 p-3 rounded-xl text-slate-700 hover:shadow-lg">
                            <i data-lucide="activity" class="w-5 h-5"></i> 
                            <span class="font-medium">Treatments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.invoices.index') }}" class="nav-link flex items-center gap-4 p-3 rounded-xl text-slate-700 hover:shadow-lg">
                            <i data-lucide="file-text" class="w-5 h-5"></i> 
                            <span class="font-medium">Invoices</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.index') }}" class="nav-link flex items-center gap-4 p-3 rounded-xl text-slate-700 hover:shadow-lg">
                            <i data-lucide="credit-card" class="w-5 h-5"></i> 
                            <span class="font-medium">Payments</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- User Section -->
        <div class="space-y-4">
            @if(session('email'))
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                                                         <p class="text-sm font-semibold text-slate-700">{{ explode('@', session('email'))[0] }}</p>
                            @if(session('role'))
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full capitalize">
                                    {{ session('role') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 p-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <i data-lucide="log-out" class="w-5 h-5"></i> 
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 main-gradient m-6 rounded-2xl shadow-2xl border border-slate-200/50 overflow-y-auto">
        <div class="p-8">
            @yield('content')
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
