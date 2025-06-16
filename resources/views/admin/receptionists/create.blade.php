@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    👩‍💼 Create New Receptionist
                </span>
            </h2>

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

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.receptionists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Personal Information --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></div>
                        Personal Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('first_name') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                   placeholder="Enter first name" required>
                            @error('first_name')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('last_name') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                   placeholder="Enter last name" required>
                            @error('last_name')
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

                {{-- Contact Information --}}
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-teal-500 rounded-full mr-3"></div>
                        Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('email') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                   placeholder="receptionist@example.com" required>
                            @error('email')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('phone') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                   placeholder="+964 750 123 4567" required>
                            @error('phone')
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

                {{-- Profile Photo --}}
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-3"></div>
                        Profile Photo
                    </h3>
                    
                    {{-- Avatar Preview --}}
                    <div id="avatarPreviewContainer" class="mb-4" style="display: none;">
                        <div class="flex items-center space-x-4 p-4 bg-green-50 rounded-xl border-2 border-green-200">
                            <img id="avatarPreview" src="" alt="Avatar Preview" 
                                 class="w-16 h-16 rounded-full object-cover border-4 border-green-300 shadow-md">
                            <div>
                                <p class="text-sm font-medium text-green-700">Photo preview</p>
                                <button type="button" id="removeAvatar" class="text-sm text-red-600 hover:text-red-800 underline">
                                    Remove photo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- File Input --}}
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                           class="w-full px-4 py-3 border-2 border-dashed border-purple-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-purple-300 bg-white file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-500 file:text-white hover:file:bg-purple-600 file:shadow-md @error('avatar') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror">
                    <p class="mt-2 text-sm text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Upload a profile photo (optional, max 5MB)
                    </p>
                    @error('avatar')
                        <p class="text-red-600 text-sm flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-between pt-6">
                    <a href="{{ route('admin.receptionists.index') }}" 
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
                            class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center">
                            <svg class="h-5 w-5 mr-3 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" />
                            </svg>
                            Create Receptionist
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Avatar preview functionality
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('avatarPreviewContainer');
            const preview = document.getElementById('avatarPreview');
            
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    e.target.value = '';
                    return;
                }
                
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB.');
                    e.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });

        // Remove avatar functionality
        document.getElementById('removeAvatar').addEventListener('click', function() {
            document.getElementById('avatar').value = '';
            document.getElementById('avatarPreviewContainer').style.display = 'none';
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['first_name', 'last_name', 'email', 'phone'];
            let hasErrors = false;
            
            requiredFields.forEach(function(fieldName) {
                const field = document.getElementById(fieldName);
                const value = field.value.trim();
                
                if (!value) {
                    field.classList.add('border-red-500');
                    hasErrors = true;
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            // Email validation
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                document.getElementById('email').classList.add('border-red-500');
                hasErrors = true;
            }
            
            if (hasErrors) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
            }
        });

        // Real-time validation feedback
        ['first_name', 'last_name', 'email', 'phone'].forEach(function(fieldName) {
            const field = document.getElementById(fieldName);
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-500');
                } else {
                    this.classList.remove('border-green-500');
                    this.classList.add('border-red-500');
                }
            });
        });
    </script>
@endsection 