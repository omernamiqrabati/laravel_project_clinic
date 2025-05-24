<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel</title>
  <script src="https://cdn.tailwindcss.com"></script> <!-- TailAdmin uses this approach :contentReference[oaicite:6]{index=6} -->
</head>
<body class="bg-gray-100">
  @yield('body')
</body>
</html>
<div class="flex h-screen">
  <aside class="w-64 bg-white border-r hidden md:block">
    <div class="p-6 text-xl font-semibold">Admin Panel</div>
    <nav class="mt-6 space-y-2">
      <a href="{{ route('admin.dentists.index') }}" class="block px-6 py-2 hover:bg-gray-200">Dentists</a>
      <a href="{{ route('admin.patients.index') }}" class="block px-6 py-2 hover:bg-gray-200">Patients</a>
      <a href="{{ route('admin.appointments.index') }}" class="block px-6 py-2 hover:bg-gray-200">Appointments</a>
      <a href="{{ route('admin.treatments.index') }}" class="block px-6 py-2 hover:bg-gray-200">Treatments</a>
      <a href="{{ route('admin.invoices.index') }}" class="block px-6 py-2 hover:bg-gray-200">Invoices</a>
      <a href="{{ route('admin.payments.index') }}" class="block px-6 py-2 hover:bg-gray-200">Payments</a>
      <a href="{{ route('admin.medical_histories.index') }}" class="block px-6 py-2 hover:bg-gray-200">Medical Histories</a>
    </nav>
  </aside>
  <div class="flex-1 flex flex-col">
    @yield('body')
  </div>
</div>
