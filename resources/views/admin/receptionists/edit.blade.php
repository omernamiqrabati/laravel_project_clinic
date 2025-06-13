@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Edit Receptionist Profile</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <h4 class="font-bold mb-2">Please fix the following errors:</h4>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.receptionists.update', $receptionist['receptionist_id']) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-semibold text-gray-700">First Name *</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $receptionist['first_name'] ?? '') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('first_name') border-red-500 @enderror" required>
                @error('first_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-semibold text-gray-700">Last Name *</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $receptionist['last_name'] ?? '') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('last_name') border-red-500 @enderror" required>
                @error('last_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $receptionist['email'] ?? '') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700">Phone *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $receptionist['phone'] ?? '') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('phone') border-red-500 @enderror" required>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Avatar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                
                {{-- Current Avatar Display --}}
                <div id="currentAvatarContainer" class="mb-3" @if(!isset($receptionist['avatar']) || is_null($receptionist['avatar']) || trim($receptionist['avatar']) === '') style="display: none;" @endif>
                    <img id="currentAvatar" src="{{ $receptionist['avatar'] ?? '' }}" alt="Current Avatar" 
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
                <p class="mt-1 text-sm text-gray-500">Upload a new photo to replace the current one (optional, max 5MB)</p>
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.receptionists.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    Update Receptionist
                </button>
            </div>
        </form>
    </div>

    <script>
        // Avatar preview functionality
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const newAvatarContainer = document.getElementById('newAvatarContainer');
            const newAvatarPreview = document.getElementById('newAvatarPreview');
            const currentAvatarContainer = document.getElementById('currentAvatarContainer');
            
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
                    newAvatarPreview.src = e.target.result;
                    newAvatarContainer.style.display = 'block';
                    if (currentAvatarContainer) {
                        currentAvatarContainer.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                newAvatarContainer.style.display = 'none';
                if (currentAvatarContainer) {
                    currentAvatarContainer.style.display = 'block';
                }
            }
        });

        // Remove new avatar functionality
        document.getElementById('removeNewAvatar').addEventListener('click', function() {
            document.getElementById('avatar').value = '';
            document.getElementById('newAvatarContainer').style.display = 'none';
            const currentAvatarContainer = document.getElementById('currentAvatarContainer');
            if (currentAvatarContainer) {
                currentAvatarContainer.style.display = 'block';
            }
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