<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <nav class="bg-white shadow p-4 mb-6">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">Dental Admin Panel</h1>
            <div class="space-x-4">
                <a href="{{ route('admin.patients.index') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600">Patients</a>
                <a href="{{ route('admin.dentists.index') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600">Dentists</a>
                <a href="{{ route('admin.appointments.index') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600">Appointments</a>
                <a href="{{ route('admin.treatments.index') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600">Treatments</a>
                <a href="{{ route('admin.invoices.index') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600">Invoices</a>
                <a href="{{ route('admin.payments.index') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600">Payments</a>
                <a href="{{ route('admin.medical_histories.index') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600">Medical Histories</a>
            </div>
        </div>
    </nav>

    <main class="max-w-full mx-auto p-6 bg-white rounded-lg shadow">
        @yield('content')
    </main>

</body>
</html>
