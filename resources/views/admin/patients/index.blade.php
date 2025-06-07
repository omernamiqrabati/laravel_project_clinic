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
                        <th class="px-5 py-3 border-b">ID</th>
                        <th class="px-5 py-3 border-b">Date of Birth</th>
                        <th class="px-5 py-3 border-b">Gender</th>
                        <th class="px-5 py-3 border-b">Address</th>
                        <th class="px-5 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-5 py-3">{{ $patient['patient_id'] }}</td>
                            <td class="px-5 py-3">{{ $patient['date_of_birth'] }}</td>
                            <td class="px-5 py-3">{{ $patient['gender'] }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $patient['address'] }}</td>

                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('admin.patients.edit', $patient['patient_id']) }}"
                                       class="text-yellow-600 hover:text-yellow-800 transition font-medium">Edit</a>

                                    <form action="{{ route('admin.patients.destroy', $patient['patient_id']) }}"
                                          method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');">
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
                                No patients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
