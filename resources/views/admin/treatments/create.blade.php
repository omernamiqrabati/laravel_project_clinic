@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    💊 Add New Treatment
                </span>
            </h1>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 text-green-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Error Messages --}}
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                    <h4 class="font-bold mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Please fix the following errors:
                    </h4>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.treatments.store') }}" method="POST" class="space-y-8">
                @csrf
                
                {{-- Treatment Information --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></div>
                        Treatment Information
                    </h3>
                    <div class="space-y-6">
                        {{-- Treatment Name --}}
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Treatment Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('name') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Enter treatment name (e.g., Root Canal, Teeth Cleaning)"
                                   required 
                                   maxlength="255">
                            @error('name')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 resize-none @error('description') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                      placeholder="Describe the treatment procedure and what it involves..."
                                      maxlength="1000">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Pricing & Duration --}}
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-teal-500 rounded-full mr-3"></div>
                        Pricing & Duration
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Cost --}}
                        <div class="space-y-2">
                            <label for="cost" class="block text-sm font-semibold text-gray-700 mb-2">
                                Cost ($) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-lg">$</span>
                                </div>
                                <input type="number" 
                                       name="cost" 
                                       id="cost" 
                                       step="0.01" 
                                       min="0"
                                       class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('cost') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                       value="{{ old('cost') }}" 
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('cost')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Duration Minutes --}}
                        <div class="space-y-2">
                            <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">
                                Duration (minutes)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="duration_minutes" 
                                       id="duration_minutes" 
                                       min="1" 
                                       max="1440"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('duration_minutes') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                       value="{{ old('duration_minutes') }}"
                                       placeholder="30">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">min</span>
                                </div>
                            </div>
                            @error('duration_minutes')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-gray-600 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Leave empty if duration is flexible (1-1440 minutes max)
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Treatment Settings --}}
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-3"></div>
                        Treatment Settings
                    </h3>
                    
                    {{-- Active Status --}}
                    <div class="space-y-2">
                        <label class="flex items-center p-4 bg-white rounded-xl border-2 border-gray-200 hover:border-purple-300 transition-all duration-300 cursor-pointer">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   class="mr-4 h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded transition-all duration-300"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-semibold text-gray-700 block">Active Treatment</span>
                                <span class="text-gray-600 text-sm">Check to make this treatment available for appointments</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-between pt-6">
                    <a href="{{ route('admin.treatments.index') }}" 
                       class="group relative px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center">
                            <svg class="h-5 w-5 mr-3 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Cancel
                        </div>
                    </a>
                    
                    <button type="submit" 
                            class="group relative px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-700 to-emerald-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center">
                            <svg class="h-5 w-5 mr-3 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" />
                            </svg>
                            Save Treatment
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
