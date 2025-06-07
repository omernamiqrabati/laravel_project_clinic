@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h2 class="text-2xl font-semibold text-blue-800 mb-6 border-b pb-2">🦷 Edit Patient Information</h2>

        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
                <strong class="block font-semibold mb-2">Please fix the following errors:</strong>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.patients.update', $patient['patient_id']) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ $patient['date_of_birth'] }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="gender" id="gender" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Gender</option>
                    <option value="Male" {{ $patient['gender'] == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $patient['gender'] == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ $patient['gender'] == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>{{ $patient['address'] }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition ease-in-out duration-150">
                    💾 Update Patient
                </button>
            </div>
        </form>
    </div>
@endsection
