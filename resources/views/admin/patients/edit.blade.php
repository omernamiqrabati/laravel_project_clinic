@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4 text-blue-700">Edit Patient</h2>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.patients.update', $patient['patient_id']) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ $patient['date_of_birth'] }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
            <select name="gender" id="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="">Select Gender</option>
                <option value="Male" {{ $patient['gender'] == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $patient['gender'] == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ $patient['gender'] == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ $patient['address'] }}</textarea>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Patient</button>
        </div>
    </form>
@endsection
