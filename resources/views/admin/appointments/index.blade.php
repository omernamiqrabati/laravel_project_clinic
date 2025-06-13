@extends('layouts.admin')

@section('content')
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-blue-800 mb-6">🦷 Dental Clinic – Appointment Management</h2>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 px-5 py-3 rounded-md bg-green-100 border border-green-400 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 px-5 py-3 rounded-md bg-red-100 border border-red-400 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Add Appointment Button --}}
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.appointments.create') }}"
               class="inline-block px-5 py-2.5 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                + Create New Appointment
            </a>
        </div>

        {{-- Appointments Table --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-md">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-blue-100 text-blue-800 text-sm font-semibold">
                    <tr>
                        <th class="px-5 py-3 border-b">ID</th>
                        <th class="px-5 py-3 border-b">Patient</th>
                        <th class="px-5 py-3 border-b">Dentist</th>
                        <th class="px-5 py-3 border-b">Treatment</th>
                        <th class="px-5 py-3 border-b">Start Time</th>
                        <th class="px-5 py-3 border-b">End Time</th>
                        <th class="px-5 py-3 border-b">Status</th>
                        <th class="px-5 py-3 border-b">Notes</th>
                        <th class="px-5 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-5 py-3" title="{{ $appointment['appointment_id'] }}">
                                {{ strlen($appointment['appointment_id']) > 8 ? substr($appointment['appointment_id'], 0, 8) . '...' : $appointment['appointment_id'] }}
                            </td>
                            <td class="px-5 py-3 text-gray-900">
                                @php
                                    $patient = collect($patients_name)->firstWhere('id', $appointment['patient_id']);
                                @endphp
                                {{ $patient ? $patient['first_name'] . ' ' . $patient['last_name'] : 'N/A' }}
                            </td>
                            <td class="px-5 py-3 text-gray-900">
                                @php
                                    $dentist = collect($dentists_name)->firstWhere('id', $appointment['dentist_id']);
                                @endphp
                                {{ $dentist ? $dentist['first_name'] . ' ' . $dentist['last_name'] : 'N/A' }}
                            </td>
                            <td class="px-5 py-3 text-gray-900">
                                @php
                                    $treatment = collect($treatments_name)->firstWhere('treatment_id', $appointment['treatment_id']);
                                @endphp
                                {{ $treatment ? $treatment['name'] : 'N/A' }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">{{ date('Y-m-d H:i', strtotime($appointment['start_time'])) }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ date('Y-m-d H:i', strtotime($appointment['end_time'])) }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $statusColors = [
                                        'Scheduled' => 'bg-green-100 text-green-800',
                                        'Cancelled' => 'bg-red-100 text-red-800',
                                        'Rescheduled' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                    $colorClass = $statusColors[$appointment['status']] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    {{ $appointment['status'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                @php
                                    $notes = $appointment['notes'] ?? 'No notes';
                                    $words = explode(' ', $notes);
                                    $shortNotes = count($words) > 3 ? implode(' ', array_slice($words, 0, 3)) . '...' : $notes;
                                @endphp
                                {{ $shortNotes }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('admin.appointments.edit', $appointment['appointment_id']) }}"
                                       class="text-yellow-600 hover:text-yellow-800 transition font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.appointments.destroy', $appointment['appointment_id']) }}"
                                          method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 transition font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-5 text-center text-gray-500">
                                No appointments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
