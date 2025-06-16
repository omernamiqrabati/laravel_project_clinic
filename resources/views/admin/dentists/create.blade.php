@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    🦷 Create New Dentist Profile
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

            <form action="{{ route('admin.dentists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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
                                   placeholder="doctor@example.com" required>
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

                {{-- Professional Information --}}
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-3"></div>
                        Professional Information
                    </h3>
                    <div class="space-y-6">
                        <!-- Specialization -->
                        <div class="space-y-2">
                            <label for="specialization" class="block text-sm font-semibold text-gray-700 mb-2">Specialization *</label>
                            <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('specialization') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                   placeholder="e.g., General Dentistry, Orthodontics, Oral Surgery" required>
                            @error('specialization')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div class="space-y-2">
                            <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">Bio</label>
                            <textarea name="bio" id="bio" rows="4"
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 resize-none"
                                      placeholder="Brief description about the dentist's experience and qualifications...">{{ old('bio') }}</textarea>
                        </div>

                        <!-- Avatar -->
                        <div class="space-y-2">
                            <label for="avatar" class="block text-sm font-semibold text-gray-700 mb-2">Profile Photo</label>
                            <input type="file" name="avatar" id="avatar" accept="image/*"
                                   class="w-full px-4 py-3 border-2 border-dashed border-purple-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-purple-300 bg-white file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-500 file:text-white hover:file:bg-purple-600 file:shadow-md">
                            <p class="mt-2 text-sm text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Upload profile photo (optional, max 5MB)
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Working Hours --}}
                @php
                    $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                @endphp

                <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-2xl border border-orange-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-red-500 rounded-full mr-3"></div>
                        Working Hours
                    </h3>
                    <p class="text-sm text-gray-600 mb-6 bg-white p-3 rounded-xl border border-orange-200">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        At least one day must have working hours set.
                    </p>
                    <div id="working-hours-container" class="space-y-4">
                        @foreach($daysOfWeek as $day)
                            <div class="bg-white p-4 rounded-xl border-2 border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-600">Day</label>
                                    <input type="text" name="working_hours[{{ $day }}][day]" value="{{ ucfirst($day) }}"
                                           readonly class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl font-medium text-gray-700">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-600">Start Time</label>
                                    <input type="time" name="working_hours[{{ $day }}][start]" value="{{ old('working_hours.'.$day.'.start') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-600">End Time</label>
                                    <input type="time" name="working_hours[{{ $day }}][end]" value="{{ old('working_hours.'.$day.'.end') }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('working_hours_json')
                        <p class="text-red-600 text-sm flex items-center mt-3">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Off Days --}}
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-6 rounded-2xl border border-yellow-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-yellow-500 to-amber-500 rounded-full mr-3"></div>
                        Off Days
                    </h3>
                    <div class="bg-white rounded-xl border-2 border-gray-100 overflow-hidden">
                        <table id="off-days-table" class="w-full">
                            <thead class="bg-gradient-to-r from-yellow-100 to-amber-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Date</th>
                                    <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Off days will be added dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="add-off-day"
                            class="mt-4 group relative px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center">
                            <svg class="h-5 w-5 mr-2 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" />
                            </svg>
                            Add Off Day
                        </div>
                    </button>
                    <input type="hidden" name="off_days" id="off_days" value='[]'>
                </div>

                <!-- Hidden JSON Input -->
                <input type="hidden" name="working_hours_json" id="working_hours_json" value="">

                {{-- Submit Buttons --}}
                <div class="flex justify-between pt-6">
                    <a href="{{ route('admin.dentists.index') }}" 
                       class="group relative px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center">
                            <svg class="h-5 w-5 mr-3 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Cancel
                        </div>
                    </a>
                    
                    <button type="submit" id="submit-btn" 
                            class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center">
                            <svg class="h-5 w-5 mr-3 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" />
                            </svg>
                            <span id="submit-text">Create Dentist</span>
                            <span id="submit-loading" class="hidden">Creating...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS Section -->
    <script>
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
            
            // Validate that at least one day has working hours
            if (Object.keys(workingHours).length === 0) {
                console.log('No working hours set, preventing form submission');
                alert('Please set working hours for at least one day.');
                // Scroll to working hours section
                document.querySelector('#working-hours-container').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                return false;
            }

            const jsonValue = JSON.stringify(workingHours);
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
                newRow.classList.add('border-t', 'border-gray-100');
                newRow.innerHTML = `
                    <td class="px-6 py-4 text-gray-700 font-medium">${newDay}</td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" class="delete-off-day px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1" data-date="${newDay}">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </td>`;
                tbody.appendChild(newRow);
            }
        });

        document.getElementById('off-days-table').addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-off-day') || event.target.closest('.delete-off-day')) {
                const button = event.target.classList.contains('delete-off-day') ? event.target : event.target.closest('.delete-off-day');
                const date = button.getAttribute('data-date');
                let offDays = JSON.parse(document.getElementById('off_days').value || '[]');
                offDays = offDays.filter(d => d !== date);
                document.getElementById('off_days').value = JSON.stringify(offDays);
                button.closest('tr').remove();
            }
        });
    </script>
@endsection