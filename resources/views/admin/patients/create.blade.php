@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4 text-blue-700">Add New Patient</h2>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.patients.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Hidden / auto-generated fields --}}
        <?php $newUuid = (string) Str::uuid(); ?>
        <input type="hidden" name="patient_id" value="{{ old('patient_id', $newUuid) }}">
        <input type="hidden" name="created_at" value="{{ old('created_at', now()) }}">
        <input type="hidden" name="updated_at" value="{{ old('updated_at', now()) }}">
        <input type="hidden" name="id" value="{{ old('id') }}">

        {{-- Personal Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" id="first_name"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('first_name') }}" required>
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" id="last_name"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('last_name') }}" required>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('email') }}" required>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" id="phone"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('phone') }}" required>
            </div>
        </div>

        {{-- Avatar Upload --}}
        <div>
            <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
            <input type="file" name="avatar" id="avatar"
                   class="mt-1 block w-full text-gray-700">
        </div>

        {{-- Date of Birth, Gender, Address --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('date_of_birth') }}" required>
            </div>
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="gender" id="gender"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Select Gender</option>
                    <option value="Male"   {{ old('gender')=='Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other"  {{ old('gender')=='Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="address" id="address" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                          required>{{ old('address') }}</textarea>
            </div>
        </div>

        {{-- Verification Flags --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="email_verified" id="email_verified" value="1"
                       {{ old('email_verified') ? 'checked' : '' }}>
                <label for="email_verified" class="text-sm text-gray-700">Email Verified</label>
            </div>
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="phone_verified" id="phone_verified" value="1"
                       {{ old('phone_verified') ? 'checked' : '' }}>
                <label for="phone_verified" class="text-sm text-gray-700">Phone Verified</label>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Create Patient
            </button>
        </div>
    </form>
@endsection
