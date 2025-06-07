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
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="bg-white w-64 min-h-screen shadow-lg p-6 flex flex-col justify-between">
        <div>            <h1 class="text-3xl font-extrabold text-blue-600 mb-10">🦷 Dental Admin</h1>
            <nav>
                <ul class="space-y-4 text-sm font-medium">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.patients.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                            <i data-lucide="users" class="w-5 h-5"></i> Patients
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.dentists.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                            <i data-lucide="user-check" class="w-5 h-5"></i> Dentists
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.appointments.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                            <i data-lucide="calendar-clock" class="w-5 h-5"></i> Appointments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.treatments.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                            <i data-lucide="activity" class="w-5 h-5"></i> Treatments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                            <i data-lucide="file-text" class="w-5 h-5"></i> Invoices
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                            <i data-lucide="credit-card" class="w-5 h-5"></i> Payments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.user_profiles.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                            <i data-lucide="clipboard-list" class="w-5 h-5"></i> User Profiles
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} Dental Clinic. All rights reserved.
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-white m-6 rounded-xl shadow-md overflow-y-auto">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
