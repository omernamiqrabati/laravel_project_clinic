@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
            <h2 class="text-3xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    🦷 Edit Patient Information
                </span>
            </h2>
            <a href="{{ route('admin.patients.index') }}" 
               class="px-4 py-2 text-gray-600 hover:text-white hover:bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl transition-all duration-300 border-2 border-gray-200 hover:border-transparent">
                ← Back to Patients
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="patientEditForm" action="{{ route('admin.patients.update', $patient['patient_id']) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Personal Info --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></div>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" id="first_name"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('first_name') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                            value="{{ old('first_name', $patient['first_name']) }}" placeholder="Enter first name" required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" id="last_name"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('last_name') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                            value="{{ old('last_name', $patient['last_name']) }}" placeholder="Enter last name" required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-teal-500 rounded-full mr-3"></div>
                    Contact Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('email') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                            value="{{ old('email', $patient['email']) }}" placeholder="patient@example.com" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                        <input type="text" name="phone" id="phone"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('phone') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                            value="{{ old('phone', $patient['phone']) }}" placeholder="+964 750 123 4567" required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Avatar --}}
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                <label class="block text-sm font-semibold text-gray-700 mb-4 flex items-center">
                    <div class="w-2 h-6 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-3"></div>
                    Profile Photo
                </label>
                
                {{-- Current Avatar Display --}}
                <div id="currentAvatarContainer" class="mb-4" @if(!isset($patient['avatar']) || !$patient['avatar']) style="display: none;" @endif>
                    <div class="flex items-center space-x-4 p-4 bg-white rounded-xl border-2 border-gray-100">
                        <img id="currentAvatar" src="{{ $patient['avatar'] ?? '' }}" alt="Current Avatar" 
                             class="w-16 h-16 rounded-full object-cover border-4 border-purple-200 shadow-md">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Current photo</p>
                            <p class="text-xs text-gray-500">Click below to change</p>
                        </div>
                    </div>
                </div>
                
                {{-- New Avatar Preview --}}
                <div id="newAvatarContainer" class="mb-4" style="display: none;">
                    <div class="flex items-center space-x-4 p-4 bg-green-50 rounded-xl border-2 border-green-200">
                        <img id="newAvatarPreview" src="" alt="New Avatar Preview" 
                             class="w-16 h-16 rounded-full object-cover border-4 border-green-300 shadow-md">
                        <div>
                            <p class="text-sm font-medium text-green-700">New photo preview</p>
                            <button type="button" id="removeNewAvatar" class="text-sm text-red-600 hover:text-red-800 underline">
                                Remove new photo
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
                    Upload a new photo to replace the current one (optional)
                </p>
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- DOB / Gender / Address --}}
            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-2xl border border-orange-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-red-500 rounded-full mr-3"></div>
                    Additional Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white @error('date_of_birth') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                            value="{{ old('date_of_birth', $patient['date_of_birth']) }}" required>
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
                        <select name="gender" id="gender"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white appearance-none bg-no-repeat bg-right pr-10 @error('gender') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                            style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 4 5&quot;><path d=&quot;M2 0L0 2h4zm0 5L0 3h4z&quot; fill=&quot;%23666&quot;/></svg>'); background-position: right 1rem center; background-size: 0.65em;" required>
                            <option value="" class="text-gray-400">Select Gender</option>
                            <option value="Male" {{ old('gender', $patient['gender']) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $patient['gender']) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $patient['gender']) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address *</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 resize-none @error('address') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                            placeholder="Enter full address" required>{{ old('address', $patient['address']) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Patient Information Display --}}
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-gray-500 to-slate-500 rounded-full mr-3"></div>
                    Patient Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded-xl border-2 border-gray-100">
                        <span class="text-sm font-semibold text-gray-600">Patient ID:</span>
                        <span class="block text-lg font-bold text-gray-800 mt-1">{{ $patient['patient_id'] }}</span>
                    </div>
                    <div class="bg-white p-4 rounded-xl border-2 border-gray-100">
                        <span class="text-sm font-semibold text-gray-600">Created:</span>
                        <span class="block text-lg font-bold text-gray-800 mt-1">{{ \Carbon\Carbon::parse($patient['created_at'])->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex justify-between pt-6">
                <a href="{{ route('admin.patients.index') }}"
                   class="group relative px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center">
                        <svg class="h-5 w-5 mr-3 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Cancel
                    </div>
                </a>
                
                <button type="submit" id="updateButton"
                    class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center">
                        <svg class="h-5 w-5 mr-3 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span id="updateButtonText">Update Patient</span>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('patientEditForm');
    const updateButton = document.getElementById('updateButton');
    const updateButtonText = document.getElementById('updateButtonText');
    
    // Loading state management
    function setLoadingState(isLoading) {
        if (isLoading) {
            updateButton.disabled = true;
            updateButton.classList.add('opacity-50', 'cursor-not-allowed');
            updateButtonText.textContent = 'Updating...';
        } else {
            updateButton.disabled = false;
            updateButton.classList.remove('opacity-50', 'cursor-not-allowed');
            updateButtonText.textContent = 'Update Patient';
        }
    }
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        setLoadingState(true);
        
        // Basic client-side validation
        const requiredFields = form.querySelectorAll('[required]');
        let hasErrors = false;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                hasErrors = true;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        // Email validation
        const emailField = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailField.value && !emailRegex.test(emailField.value)) {
            emailField.classList.add('border-red-500');
            hasErrors = true;
        }
        
        if (hasErrors) {
            e.preventDefault();
            setLoadingState(false);
            alert('Please fill in all required fields correctly.');
        }
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500') && this.value.trim()) {
                this.classList.remove('border-red-500');
            }
        });
    });
    
    // File upload preview
    const avatarInput = document.getElementById('avatar');
    const currentAvatarContainer = document.getElementById('currentAvatarContainer');
    const newAvatarContainer = document.getElementById('newAvatarContainer');
    const newAvatarPreview = document.getElementById('newAvatarPreview');
    const removeNewAvatarBtn = document.getElementById('removeNewAvatar');
    
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    newAvatarPreview.src = e.target.result;
                    newAvatarContainer.style.display = 'block';
                    // Hide current avatar when new one is selected
                    if (currentAvatarContainer) {
                        currentAvatarContainer.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
                
                console.log('Avatar file selected:', file.name, 'Size:', (file.size / 1024 / 1024).toFixed(2) + 'MB');
            } else {
                // No file selected, hide preview and show current avatar
                newAvatarContainer.style.display = 'none';
                if (currentAvatarContainer) {
                    currentAvatarContainer.style.display = 'block';
                }
            }
        });
        
        // Remove new avatar button
        if (removeNewAvatarBtn) {
            removeNewAvatarBtn.addEventListener('click', function() {
                avatarInput.value = '';
                newAvatarContainer.style.display = 'none';
                if (currentAvatarContainer) {
                    currentAvatarContainer.style.display = 'block';
                }
            });
        }
    }
});
</script>
@endsection
