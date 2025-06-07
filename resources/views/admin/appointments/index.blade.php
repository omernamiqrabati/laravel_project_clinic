@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-blue-700">Appointments</h2>
        <a href="{{ route('admin.appointments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Create New Appointment
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dentist</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Treatment</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Start Time</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">End Time</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($appointments as $appointment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment['appointment_id'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    @foreach($patients_name as $patient)
                        @if($patient['id'] == $appointment['patient_id'])
                            <option value="{{ $patient['id'] }}" selected>
                                {{ $patient['first_name'] . ' ' . $patient['last_name'] }}
                            </option>
                        @endif
                    @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @foreach($dentists_name as $dentist)
                        @if($dentist['id'] == $appointment['dentist_id'])
                            <option value="{{ $dentist['id'] }}" selected>
                                {{ $dentist['first_name'] . ' ' . $dentist['last_name'] }}
                            </option>
                        @endif
                    @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                   @foreach($treatments_name as $treatment)
                                        @if($treatment['treatment_id'] == $appointment['treatment_id'])
                                            <option value="{{ $treatment['treatment_id'] }}" selected>
                                                {{ $treatment['name'] }}
                                            </option>
                                        @endif
                                    @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('Y-m-d H:i', strtotime($appointment['start_time'])) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('Y-m-d H:i', strtotime($appointment['end_time'])) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $appointment['status'] == 'Scheduled' ? 'bg-green-100 text-green-800' : 
                                   ($appointment['status'] == 'Cancelled' ? 'bg-red-100 text-red-800' : 
                                   ($appointment['status'] == 'Rescheduled' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-gray-100 text-gray-800')) }}">
                                {{ $appointment['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.appointments.edit', $appointment['appointment_id']) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('admin.appointments.destroy', $appointment['appointment_id']) }}" 
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                        onclick="return confirm('Are you sure you want to delete this appointment?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No appointments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection