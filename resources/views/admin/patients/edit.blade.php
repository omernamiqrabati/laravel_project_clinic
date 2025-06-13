@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-md rounded-2xl p-8">
        <div class="flex justify-between items-center mb-6 border-b pb-2">
            <h2 class="text-2xl font-semibold text-blue-800">🦷 Edit Patient Information</h2>
            <a href="{{ route('admin.patients.index') }}" 
               class="text-gray-600 hover:text-gray-800 transition">
                ← Back to Patients
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6">
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="patientEditForm" action="{{ route('admin.patients.update', $patient['patient_id']) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Personal Info --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                        <input type="text" name="first_name" id="first_name"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror"
                            value="{{ old('first_name', $patient['first_name']) }}" required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                        <input type="text" name="last_name" id="last_name"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror"
                            value="{{ old('last_name', $patient['last_name']) }}" required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Contact Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" name="email" id="email"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            value="{{ old('email', $patient['email']) }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                        <input type="text" name="phone" id="phone"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                            value="{{ old('phone', $patient['phone']) }}" required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Avatar --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                
                {{-- Current Avatar Display --}}
                <div id="currentAvatarContainer" class="mb-3" @if(!isset($patient['avatar']) || !$patient['avatar']) style="display: none;" @endif>
                    <img id="currentAvatar" src="{{ $patient['avatar'] ?? '' }}" alt="Current Avatar" 
                         class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                    <p class="text-sm text-gray-600 mt-1">Current photo</p>
                </div>
                
                {{-- New Avatar Preview --}}
                <div id="newAvatarContainer" class="mb-3" style="display: none;">
                    <img id="newAvatarPreview" src="" alt="New Avatar Preview" 
                         class="w-20 h-20 rounded-full object-cover border-2 border-green-200">
                    <p class="text-sm text-green-600 mt-1">New photo preview</p>
                    <button type="button" id="removeNewAvatar" class="text-sm text-red-600 hover:text-red-800 mt-1">
                        Remove new photo
                    </button>
                </div>
                
                {{-- File Input --}}
                <input type="file" name="avatar" id="avatar" accept="image/*"
                    class="mt-1 block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('avatar') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Upload a new photo to replace the current one (optional)</p>
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- DOB / Gender / Address --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('date_of_birth') border-red-500 @enderror"
                            value="{{ old('date_of_birth', $patient['date_of_birth']) }}" required>
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
                        <select name="gender" id="gender"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $patient['gender']) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $patient['gender']) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $patient['gender']) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                        <textarea name="address" id="address" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                            required>{{ old('address', $patient['address']) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Patient Information Display --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Patient Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-600">Patient ID:</span>
                        <span class="text-gray-800 ml-2">{{ $patient['patient_id'] }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Created:</span>
                        <span class="text-gray-800 ml-2">{{ \Carbon\Carbon::parse($patient['created_at'])->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex justify-between pt-4">
                <a href="{{ route('admin.patients.index') }}"
                   class="inline-flex items-center px-6 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg shadow hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Cancel
                </a>
                
                <button type="submit" id="updateButton"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span id="updateButtonText">Update Patient</span>
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
