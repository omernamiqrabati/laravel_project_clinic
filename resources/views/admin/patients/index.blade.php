@extends('layouts.admin')

@section('content')
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-blue-800 mb-6">🦷 Dental Clinic – Patient Management</h2>

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

        @if(session('warning'))
            <div class="mb-4 px-5 py-3 rounded-md bg-yellow-100 border border-yellow-400 text-yellow-800">
                {{ session('warning') }}
            </div>
        @endif

        {{-- Add Patient Button --}}
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.patients.create') }}"
               class="inline-block px-5 py-2.5 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                + Add New Patient
            </a>
        </div>

        {{-- Patient Table --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-md">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-blue-100 text-blue-800 text-sm font-semibold">
                    <tr>
                        <th class="px-5 py-3 border-b">Photo</th>
                        <th class="px-5 py-3 border-b">Full Name</th>
                        <th class="px-5 py-3 border-b">Email</th>
                        <th class="px-5 py-3 border-b">Phone</th>
                        <th class="px-5 py-3 border-b">Gender</th>
                        <th class="px-5 py-3 border-b">Date of Birth</th>
                        <th class="px-5 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-5 py-3">
                                @if(!empty($patient['avatar']))
                                    <img src="{{ $patient['avatar'] }}" alt="{{ $patient['first_name'] }} {{ $patient['last_name'] }}" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 shadow-sm">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-medium text-gray-900">
                                {{ $patient['first_name'] }} {{ $patient['last_name'] }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $patient['email'] }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $patient['phone'] ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-3">
                                @if(isset($patient['gender']))
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                        {{ $patient['gender'] }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                @if(isset($patient['date_of_birth']))
                                    {{ \Carbon\Carbon::parse($patient['date_of_birth'])->format('M d, Y') }}
                                    <br>
                                    <small class="text-gray-500">
                                        ({{ \Carbon\Carbon::parse($patient['date_of_birth'])->age }} years old)
                                    </small>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('admin.patients.edit', $patient['patient_id']) }}"
                                       class="text-yellow-600 hover:text-yellow-800 transition font-medium">Edit</a>

                                    <form action="{{ route('admin.patients.destroy', $patient['patient_id']) }}"
                                          method="POST" onsubmit="return confirm('⚠️ WARNING: This will permanently delete {{ $patient['first_name'] }} {{ $patient['last_name'] }} and ALL related data including:\n\n• All appointments\n• Clinical records\n• Appointment ratings\n• Invoices\n• User profile\n\nThis action CANNOT be undone. Are you absolutely sure?');">
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
                            <td colspan="7" class="px-5 py-5 text-center text-gray-500">
                                No patients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
