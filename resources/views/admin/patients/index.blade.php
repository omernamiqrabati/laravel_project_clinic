@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4 text-blue-700">Patient List</h2>

    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.patients.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add New Patient</a>
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

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-blue-100 text-left text-sm font-semibold text-blue-700">
                    <th class="py-2 px-4 border-b">Patient ID</th>
                    <th class="py-2 px-4 border-b">Date of Birth</th>
                    <th class="py-2 px-4 border-b">Gender</th>
                    <th class="py-2 px-4 border-b">Address</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach ($patients as $patient)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $patient['patient_id'] }}</td>
                        <td class="py-2 px-4 border-b">{{ $patient['date_of_birth'] }}</td>
                        <td class="py-2 px-4 border-b">{{ $patient['gender'] }}</td>
                        <td class="py-2 px-4 border-b">{{ $patient['address'] }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('admin.patients.edit', $patient['patient_id']) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                            |
                            <form action="{{ route('admin.patients.destroy', $patient['patient_id']) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
