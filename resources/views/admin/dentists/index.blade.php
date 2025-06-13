@extends('layouts.admin')

@section('content')
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-blue-800 mb-6">🦷 Dental Clinic – Dentist Management</h2>

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

        {{-- Add Dentist Button --}}
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.dentists.create') }}"
               class="inline-block px-5 py-2.5 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                + Add New Dentist
            </a>
        </div>

        {{-- Dentist Table --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-md">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-blue-100 text-blue-800 text-sm font-semibold">
                    <tr>
                        <th class="px-5 py-3 border-b">Photo</th>
                        <th class="px-5 py-3 border-b">Full Name</th>
                        <th class="px-5 py-3 border-b">Email</th>
                        <th class="px-5 py-3 border-b">Specialization</th>
                        <th class="px-5 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dentists as $dentist)
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-5 py-3">
                                @if(!empty($dentist['avatar']))
                                    <img src="{{ $dentist['avatar'] }}" alt="{{ $dentist['first_name'] }} {{ $dentist['last_name'] }}" 
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
                                {{ $dentist['first_name'] }} {{ $dentist['last_name'] }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $dentist['email'] }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ $dentist['specialization'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('admin.dentists.edit', $dentist['dentist_id']) }}"
                                       class="text-yellow-600 hover:text-yellow-800 transition font-medium">Edit</a>

                                    <form action="{{ route('admin.dentists.destroy', $dentist['dentist_id']) }}"
                                          method="POST" onsubmit="return confirm('⚠️ WARNING: This will permanently delete {{ $dentist['first_name'] }} {{ $dentist['last_name'] }} and ALL related data including:\n\n• All appointments\n• Clinical records\n• Appointment ratings\n• Invoices\n• User profile\n\nThis action CANNOT be undone. Are you absolutely sure?');">
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
                            <td colspan="5" class="px-5 py-5 text-center text-gray-500">
                                No dentists found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
