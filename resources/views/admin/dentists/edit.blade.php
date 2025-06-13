@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Edit Dentist Profile</h2>

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

        <form action="{{ route('admin.dentists.update', $dentist['dentist_id']) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-semibold text-gray-700">First Name *</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $dentist['first_name'] ?? '') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('first_name') border-red-500 @enderror" required>
                @error('first_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-semibold text-gray-700">Last Name *</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $dentist['last_name'] ?? '') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('last_name') border-red-500 @enderror" required>
                @error('last_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $dentist['email'] ?? '') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700">Phone *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $dentist['phone'] ?? '') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('phone') border-red-500 @enderror" required>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Specialization -->
            <div>
                <label for="specialization" class="block text-sm font-semibold text-gray-700">Specialization *</label>
                <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $dentist['specialization']) }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 @error('specialization') border-red-500 @enderror" required>
                @error('specialization')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-semibold text-gray-700">Bio</label>
                <textarea name="bio" id="bio" rows="3"
                          class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('bio', $dentist['bio']) }}</textarea>
            </div>

            <!-- Avatar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                
                {{-- Current Avatar Display --}}
                <div id="currentAvatarContainer" class="mb-3" @if(!isset($dentist['avatar']) || is_null($dentist['avatar']) || trim($dentist['avatar']) === '') style="display: none;" @endif>
                    <img id="currentAvatar" src="{{ $dentist['avatar'] ?? '' }}" alt="Current Avatar" 
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

            <!-- Working Hours -->
            @php
                $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $workingHours = $dentist['working_hours'] ?? [];
            @endphp

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Working Hours *</label>
                <p class="text-sm text-gray-600 mb-3">At least one day must have working hours set.</p>
                <div id="working-hours-container" class="space-y-4">
                    @foreach($daysOfWeek as $day)
                        @php
                            $dayData = $workingHours[$day] ?? ['start' => '', 'end' => ''];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Day</label>
                                <input type="text" name="working_hours[{{ $day }}][day]" value="{{ ucfirst($day) }}"
                                       readonly class="w-full bg-gray-100 border rounded-md p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Start Time</label>
                                <input type="time" name="working_hours[{{ $day }}][start]" value="{{ old('working_hours.'.$day.'.start', $dayData['start']) }}"
                                       class="w-full border rounded-md p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">End Time</label>
                                <input type="time" name="working_hours[{{ $day }}][end]" value="{{ old('working_hours.'.$day.'.end', $dayData['end']) }}"
                                       class="w-full border rounded-md p-2">
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('working_hours_json')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Off Days -->
            <div>
                <label for="off_days" class="block text-sm font-semibold text-gray-700 mb-2">Off Days</label>
                <table id="off-days-table" class="w-full text-sm border border-gray-300 rounded overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Date</th>
                            <th class="px-4 py-2 border text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($dentist['off_days'] ?? []) as $offDay)
                            <tr class="border-t">
                                <td class="px-4 py-2 border">{{ $offDay }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <button type="button" class="delete-off-day px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600" data-date="{{ $offDay }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="button" id="add-off-day"
                        class="mt-3 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Add Off Day
                </button>
                <input type="hidden" name="off_days" id="off_days" value='{{ json_encode($dentist['off_days'] ?? []) }}'>
            </div>

            <!-- Hidden JSON Input -->
            <input type="hidden" name="working_hours_json" id="working_hours_json" value="">
            @error('working_hours_json')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.dentists.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                    <span id="submit-text">Update Dentist</span>
                    <span id="submit-loading" class="hidden">Updating...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- JS Section -->
    <script>
        // Debug: Log the working hours data from PHP on page load
        console.log('Working hours from database:', @json($workingHours));
        
        // Initialize working_hours_json on page load with existing data
        document.addEventListener('DOMContentLoaded', function() {
            const existingWorkingHours = @json($workingHours);
            if (existingWorkingHours && Object.keys(existingWorkingHours).length > 0) {
                document.getElementById('working_hours_json').value = JSON.stringify(existingWorkingHours);
                console.log('Initialized working_hours_json with existing data:', JSON.stringify(existingWorkingHours));
            }
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            console.log('Form submit event triggered');
            
            // Prevent the default submission first
            event.preventDefault();
            
            const rows = document.querySelectorAll('#working-hours-container .grid');
            const workingHours = {};

            rows.forEach((row, index) => {
                const dayInput = row.querySelector('input[name*="[day]"]');
                const startInput = row.querySelector('input[name*="[start]"]');
                const endInput = row.querySelector('input[name*="[end]"]');
                
                console.log(`Processing row ${index}:`, {
                    dayInput: dayInput ? dayInput.value : 'not found',
                    startInput: startInput ? startInput.value : 'not found',
                    endInput: endInput ? endInput.value : 'not found'
                });
                
                if (dayInput && startInput && endInput) {
                    const day = dayInput.value.toLowerCase();
                    const start = startInput.value;
                    const end = endInput.value;
                    
                    console.log(`Day ${day}:`, { start, end });
                    
                    if (start && end) {
                        workingHours[day] = { start, end };
                        console.log(`Added working hours for ${day}:`, { start, end });
                    }
                }
            });

            console.log('Working hours collected:', workingHours);
            
            // Check if we have working hours from the form or from the initial load
            let finalWorkingHours = workingHours;
            if (Object.keys(finalWorkingHours).length === 0) {
                // Try to get from the hidden field if form collection failed
                const existingValue = document.getElementById('working_hours_json').value;
                if (existingValue) {
                    try {
                        const existingData = JSON.parse(existingValue);
                        if (existingData && Object.keys(existingData).length > 0) {
                            finalWorkingHours = existingData;
                            console.log('Using existing working hours from hidden field:', finalWorkingHours);
                        }
                    } catch (e) {
                        console.error('Error parsing existing working hours:', e);
                    }
                }
            }
            
            // Validate that at least one day has working hours
            if (Object.keys(finalWorkingHours).length === 0) {
                console.log('No working hours set, preventing form submission');
                alert('Please set working hours for at least one day.');
                // Scroll to working hours section
                document.querySelector('#working-hours-container').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                return false;
            }

            const jsonValue = JSON.stringify(finalWorkingHours);
            console.log('Setting working_hours_json value:', jsonValue);
            document.getElementById('working_hours_json').value = jsonValue;
            
            // Verify the value was set
            const verifyValue = document.getElementById('working_hours_json').value;
            console.log('Verified working_hours_json value:', verifyValue);
            
            // Final validation before submission
            if (!verifyValue || verifyValue === '{}' || verifyValue === '[]') {
                alert('Error: Working hours data is not properly set. Please try again.');
                console.error('Working hours JSON is empty or invalid:', verifyValue);
                return false;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const submitLoading = document.getElementById('submit-loading');
            
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            // Now submit the form
            console.log('Submitting form with working hours:', verifyValue);
            event.target.submit();
        });

        document.getElementById('add-off-day').addEventListener('click', function() {
            const offDaysInput = document.getElementById('off_days');
            const tbody = document.querySelector('#off-days-table tbody');
            let offDays = JSON.parse(offDaysInput.value || '[]');

            const newDay = prompt('Enter a new off day (e.g., "2025-05-20"):', new Date().toISOString().split('T')[0]);
            if (newDay && !offDays.includes(newDay)) {
                offDays.push(newDay);
                offDaysInput.value = JSON.stringify(offDays);

                const newRow = document.createElement('tr');
                newRow.classList.add('border-t');
                newRow.innerHTML = `
                    <td class="px-4 py-2 border">${newDay}</td>
                    <td class="px-4 py-2 border text-center">
                        <button type="button" class="delete-off-day px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600" data-date="${newDay}">Delete</button>
                    </td>`;
                tbody.appendChild(newRow);
            }
        });

        document.getElementById('off-days-table').addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-off-day')) {
                const date = event.target.getAttribute('data-date');
                let offDays = JSON.parse(document.getElementById('off_days').value || '[]');
                offDays = offDays.filter(d => d !== date);
                document.getElementById('off_days').value = JSON.stringify(offDays);
                event.target.closest('tr').remove();
            }
        });

        // Avatar preview functionality
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
    </script>
@endsection
