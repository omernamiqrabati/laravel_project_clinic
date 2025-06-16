@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-800 mb-2">🦷 Dental Clinic Dashboard</h1>
        <p class="text-gray-600">Welcome to your admin panel. Here's an overview of your clinic's activities.</p>
    </div>

    {{-- Error Message --}}
    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Warning:</strong> {{ $error }}
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Patients Card --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Patients</p>
                    <p class="text-3xl font-bold">{{ $stats['total_patients'] }}</p>
                </div>
                <div class="p-3 bg-blue-400 bg-opacity-30 rounded-full">
                    <i data-lucide="users" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        {{-- Dentists Card --}}
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Dentists</p>
                    <p class="text-3xl font-bold">{{ $stats['total_dentists'] }}</p>
                </div>
                <div class="p-3 bg-green-400 bg-opacity-30 rounded-full">
                    <i data-lucide="user-check" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        {{-- Appointments Card --}}
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Total Appointments</p>
                    <p class="text-3xl font-bold">{{ $stats['total_appointments'] }}</p>
                    <p class="text-yellow-100 text-xs mt-1">Pending: {{ $pendingAppointments }}</p>
                </div>
                <div class="p-3 bg-yellow-400 bg-opacity-30 rounded-full">
                    <i data-lucide="calendar-clock" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        {{-- Payments Card --}}
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">{{ $stats['current_month'] }} Payments</p>
                    <p class="text-3xl font-bold">${{ number_format($stats['total_payment_amount'], 2) }}</p>
                    <p class="text-purple-100 text-xs mt-1">{{ $stats['current_month_payments'] }} payments this month</p>
                </div>
                <div class="p-3 bg-purple-400 bg-opacity-30 rounded-full">
                    <i data-lucide="credit-card" class="w-8 h-8"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.patients.create') ?? '#' }}" 
               class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                <i data-lucide="user-plus" class="w-8 h-8 text-blue-600 mb-2"></i>
                <span class="text-sm font-medium text-blue-700">Add Patient</span>
            </a>
            
            <a href="{{ route('admin.appointments.create') ?? '#' }}" 
               class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <i data-lucide="calendar-plus" class="w-8 h-8 text-green-600 mb-2"></i>
                <span class="text-sm font-medium text-green-700">Book Appointment</span>
            </a>
            
            <a href="{{ route('admin.treatments.create') ?? '#' }}" 
               class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                <i data-lucide="activity" class="w-8 h-8 text-yellow-600 mb-2"></i>
                <span class="text-sm font-medium text-yellow-700">Add Treatment</span>
            </a>
            
            <a href="{{ route('admin.invoices.create') ?? '#' }}" 
               class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                <i data-lucide="file-plus" class="w-8 h-8 text-purple-600 mb-2"></i>
                <span class="text-sm font-medium text-purple-700">Create Invoice</span>
            </a>
        </div>
    </div>

    {{-- Recent Activity & System Overview --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Appointments --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Appointments</h3>
            </div>
            <div class="p-6">
                @if(count($recentAppointments) > 0)
                    <div class="space-y-4">
                        @foreach($recentAppointments as $appointment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        Patient ID: {{ $appointment['patient_id'] ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $appointment['appointment_date'] ?? 'No date' }} at {{ $appointment['appointment_time'] ?? 'No time' }}
                                    </p>
                                </div>
                                <div>
                                    @php
                                        $statusColors = [
                                            'Scheduled' => 'bg-blue-100 text-blue-800',
                                            'Completed' => 'bg-green-100 text-green-800',
                                            'Cancelled' => 'bg-red-100 text-red-800',
                                            'No-show' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $status = $appointment['status'] ?? 'Unknown';
                                        $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                        {{ $status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.appointments.index') ?? '#' }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View all appointments →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="calendar-x" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                        <p class="text-gray-600">No recent appointments</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- System Overview --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">System Overview</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Treatments</span>
                        <span class="font-semibold text-gray-900">{{ $stats['total_treatments'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Invoices</span>
                        <span class="font-semibold text-gray-900">{{ $stats['total_invoices'] }}</span>
                    </div>
                    <hr class="my-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">📊 Clinic Status</h4>
                        <p class="text-sm text-blue-600">
                            Your clinic is operational with {{ $stats['total_dentists'] }} dentist(s) 
                            serving {{ $stats['total_patients'] }} patient(s).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigate to - Table View --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Admin Tables Overview</h3>
        
        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-blue-50 text-blue-800 text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 border-b border-blue-200">Module</th>
                        <th class="px-4 py-3 border-b border-blue-200">Total Records</th>
                        <th class="px-4 py-3 border-b border-blue-200">Status</th>
                        <th class="px-4 py-3 border-b border-blue-200 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Patients</p>
                                    <p class="text-xs text-gray-500">Patient management</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-2xl font-bold text-blue-600">{{ $stats['total_patients'] }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['total_patients'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $stats['total_patients'] > 0 ? 'Active' : 'Empty' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.patients.index') ?? '#' }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</a>
                                <a href="{{ route('admin.patients.create') ?? '#' }}" 
                                   class="text-green-600 hover:text-green-800 font-medium text-sm">Add</a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg mr-3">
                                    <i data-lucide="user-check" class="w-5 h-5 text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Dentists</p>
                                    <p class="text-xs text-gray-500">Staff management</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-2xl font-bold text-green-600">{{ $stats['total_dentists'] }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['total_dentists'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $stats['total_dentists'] > 0 ? 'Active' : 'Empty' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.dentists.index') ?? '#' }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</a>
                                <a href="{{ route('admin.dentists.create') ?? '#' }}" 
                                   class="text-green-600 hover:text-green-800 font-medium text-sm">Add</a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-teal-50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-teal-100 rounded-lg mr-3">
                                    <i data-lucide="user-cog" class="w-5 h-5 text-teal-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Receptionists</p>
                                    <p class="text-xs text-gray-500">Front desk staff</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-2xl font-bold text-teal-600">{{ $stats['total_receptionists'] ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @if(isset($stats['total_receptionists']))
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['total_receptionists'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $stats['total_receptionists'] > 0 ? 'Active' : 'Empty' }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    Not Available
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.receptionists.index') ?? '#' }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</a>
                                <a href="{{ route('admin.receptionists.create') ?? '#' }}" 
                                   class="text-green-600 hover:text-green-800 font-medium text-sm">Add</a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-yellow-50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                    <i data-lucide="calendar-clock" class="w-5 h-5 text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Appointments</p>
                                    <p class="text-xs text-gray-500">Scheduling system</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-2xl font-bold text-yellow-600">{{ $stats['total_appointments'] }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $pendingAppointments > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $pendingAppointments > 0 ? $pendingAppointments . ' Pending' : 'Up to date' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.appointments.index') ?? '#' }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</a>
                                <a href="{{ route('admin.appointments.create') ?? '#' }}" 
                                   class="text-green-600 hover:text-green-800 font-medium text-sm">Book</a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                                    <i data-lucide="activity" class="w-5 h-5 text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Treatments</p>
                                    <p class="text-xs text-gray-500">Medical procedures</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-2xl font-bold text-indigo-600">{{ $stats['total_treatments'] }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['total_treatments'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $stats['total_treatments'] > 0 ? 'Active' : 'Empty' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.treatments.index') ?? '#' }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</a>
                                <a href="{{ route('admin.treatments.create') ?? '#' }}" 
                                   class="text-green-600 hover:text-green-800 font-medium text-sm">Add</a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-orange-50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-orange-100 rounded-lg mr-3">
                                    <i data-lucide="file-text" class="w-5 h-5 text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Invoices</p>
                                    <p class="text-xs text-gray-500">Billing system</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-2xl font-bold text-orange-600">{{ $stats['total_invoices'] }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['total_invoices'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $stats['total_invoices'] > 0 ? 'Active' : 'Empty' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.invoices.index') ?? '#' }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</a>
                                <a href="{{ route('admin.invoices.create') ?? '#' }}" 
                                   class="text-green-600 hover:text-green-800 font-medium text-sm">Create</a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-purple-50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                    <i data-lucide="credit-card" class="w-5 h-5 text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Payments</p>
                                    <p class="text-xs text-gray-500">Financial records</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div>
                                <span class="text-2xl font-bold text-purple-600">{{ $stats['total_payments'] }}</span>
                                <p class="text-xs text-gray-500">${{ number_format($stats['total_payment_amount'], 2) }} total</p>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['current_month_payments'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $stats['current_month_payments'] > 0 ? $stats['current_month_payments'] . ' This Month' : 'No Recent' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.payments.index') ?? '#' }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</a>
                                <a href="{{ route('admin.payments.create') ?? '#' }}" 
                                   class="text-green-600 hover:text-green-800 font-medium text-sm">Record</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden space-y-3">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <i data-lucide="users" class="w-5 h-5 text-blue-600 mr-2"></i>
                        <span class="font-medium text-gray-900">Patients</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ $stats['total_patients'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['total_patients'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $stats['total_patients'] > 0 ? 'Active' : 'Empty' }}
                    </span>
                    <div class="space-x-2">
                        <a href="{{ route('admin.patients.index') ?? '#' }}" class="text-blue-600 text-sm font-medium">View</a>
                        <a href="{{ route('admin.patients.create') ?? '#' }}" class="text-green-600 text-sm font-medium">Add</a>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <i data-lucide="user-check" class="w-5 h-5 text-green-600 mr-2"></i>
                        <span class="font-medium text-gray-900">Dentists</span>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ $stats['total_dentists'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['total_dentists'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $stats['total_dentists'] > 0 ? 'Active' : 'Empty' }}
                    </span>
                    <div class="space-x-2">
                        <a href="{{ route('admin.dentists.index') ?? '#' }}" class="text-blue-600 text-sm font-medium">View</a>
                        <a href="{{ route('admin.dentists.create') ?? '#' }}" class="text-green-600 text-sm font-medium">Add</a>
                    </div>
                </div>
            </div>
            
            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <i data-lucide="user-cog" class="w-5 h-5 text-teal-600 mr-2"></i>
                        <span class="font-medium text-gray-900">Receptionists</span>
                    </div>
                    <span class="text-lg font-bold text-teal-600">{{ $stats['total_receptionists'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    @if(isset($stats['total_receptionists']))
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['total_receptionists'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $stats['total_receptionists'] > 0 ? 'Active' : 'Empty' }}
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Not Available
                        </span>
                    @endif
                    <div class="space-x-2">
                        <a href="{{ route('admin.receptionists.index') ?? '#' }}" class="text-blue-600 text-sm font-medium">View</a>
                        <a href="{{ route('admin.receptionists.create') ?? '#' }}" class="text-green-600 text-sm font-medium">Add</a>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <i data-lucide="calendar-clock" class="w-5 h-5 text-yellow-600 mr-2"></i>
                        <span class="font-medium text-gray-900">Appointments</span>
                    </div>
                    <span class="text-lg font-bold text-yellow-600">{{ $stats['total_appointments'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $pendingAppointments > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                        {{ $pendingAppointments > 0 ? $pendingAppointments . ' Pending' : 'Up to date' }}
                    </span>
                    <div class="space-x-2">
                        <a href="{{ route('admin.appointments.index') ?? '#' }}" class="text-blue-600 text-sm font-medium">View</a>
                        <a href="{{ route('admin.appointments.create') ?? '#' }}" class="text-green-600 text-sm font-medium">Book</a>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <i data-lucide="credit-card" class="w-5 h-5 text-purple-600 mr-2"></i>
                        <span class="font-medium text-gray-900">Payments</span>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-bold text-purple-600">{{ $stats['total_payments'] }}</span>
                        <p class="text-xs text-gray-500">${{ number_format($stats['total_payment_amount'], 2) }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stats['current_month_payments'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $stats['current_month_payments'] > 0 ? $stats['current_month_payments'] . ' This Month' : 'No Recent' }}
                    </span>
                    <div class="space-x-2">
                        <a href="{{ route('admin.payments.index') ?? '#' }}" class="text-blue-600 text-sm font-medium">View</a>
                        <a href="{{ route('admin.payments.create') ?? '#' }}" class="text-green-600 text-sm font-medium">Record</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons after page load
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection