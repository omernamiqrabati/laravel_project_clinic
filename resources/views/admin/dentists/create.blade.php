@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4 text-blue-700">Add New Dentist</h2>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.dentists.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
            <input type="text" name="specialization" id="specialization" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('specialization') }}" required>
        </div>

        <div>
            <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
            <textarea name="bio" id="bio" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('bio') }}</textarea>
        </div>

        <div>
            <label for="working_hours" class="block text-sm font-medium text-gray-700">Working Hours</label>
            <input type="text" name="working_hours" id="working_hours" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('working_hours') }}" required>
        </div>

        <div>
            <label for="off_days" class="block text-sm font-medium text-gray-700">Off Days</label>
            <input type="text" name="off_days" id="off_days" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('off_days') }}">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Dentist</button>
        </div>
    </form>
@endsection
