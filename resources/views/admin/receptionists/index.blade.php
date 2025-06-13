@extends('layouts.admin')

@section('content')
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-blue-800 mb-6">👥 Dental Clinic – Receptionist Management</h2>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 px-5 py-3 rounded-md bg-green-100 border border-green-400 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 px-5 py-3 rounded-md bg-yellow-100 border border-yellow-400 text-yellow-800">
                {{ session('warning') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 px-5 py-3 rounded-md bg-red-100 border border-red-400 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Add Receptionist Button --}}
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.receptionists.create') }}"
               class="inline-block px-5 py-2.5 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                + Add New Receptionist
            </a>
        </div>

        {{-- Receptionist Table --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-md">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-blue-100 text-blue-800 text-sm font-semibold">
                    <tr>
                        <th class="px-5 py-3 border-b">Full Name</th>
                        <th class="px-5 py-3 border-b">Email</th>
                        <th class="px-5 py-3 border-b">Phone</th>
                        <th class="px-5 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receptionists as $receptionist)
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-5 py-3 font-medium text-gray-900">
                                {{ ($receptionist['first_name'] ?? 'No name') . ' ' . ($receptionist['last_name'] ?? '') }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $receptionist['email'] ?? 'No email' }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $receptionist['phone'] ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('admin.receptionists.edit', $receptionist['receptionist_id']) }}"
                                       class="text-yellow-600 hover:text-yellow-800 transition font-medium">Edit</a>

                                    <form action="{{ route('admin.receptionists.destroy', $receptionist['receptionist_id']) }}"
                                          method="POST" onsubmit="return confirm('⚠️ WARNING: This will permanently delete {{ ($receptionist['first_name'] ?? 'No name') . ' ' . ($receptionist['last_name'] ?? '') }} and ALL related data including:\n\n• User profile\n• All associated data\n\nThis action CANNOT be undone. Are you absolutely sure?');">
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
                            <td colspan="4" class="px-5 py-5 text-center text-gray-500">
                                No receptionists found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection 