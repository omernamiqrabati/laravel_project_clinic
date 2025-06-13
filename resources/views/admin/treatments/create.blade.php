@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Add New Treatment</h1>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Messages --}}
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.treatments.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 space-y-6">
                @csrf
                
                {{-- Treatment Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Treatment Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                           value="{{ old('name') }}" 
                           required 
                           maxlength="255">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                              maxlength="1000">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cost --}}
                <div>
                    <label for="cost" class="block text-sm font-semibold text-gray-700 mb-2">
                        Cost ($) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="cost" 
                           id="cost" 
                           step="0.01" 
                           min="0"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cost') border-red-500 @enderror" 
                           value="{{ old('cost') }}" 
                           required>
                    @error('cost')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Duration Minutes --}}
                <div>
                    <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Duration (minutes)
                    </label>
                    <input type="number" 
                           name="duration_minutes" 
                           id="duration_minutes" 
                           min="1" 
                           max="1440"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('duration_minutes') border-red-500 @enderror" 
                           value="{{ old('duration_minutes') }}">
                    @error('duration_minutes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Leave empty if duration is flexible (1-1440 minutes max)</p>
                </div>

                {{-- Active Status --}}
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               value="1"
                               class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="text-sm font-semibold text-gray-700">Active Treatment</span>
                    </label>
                    <p class="text-gray-500 text-sm mt-1">Check to make this treatment available for appointments</p>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-between items-center pt-4">
                    <a href="{{ route('admin.treatments.index') }}" 
                       class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition duration-200">
                        Save Treatment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
