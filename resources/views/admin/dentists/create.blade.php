@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Create New Dentist Profile</h2>

        <!-- Fill with Sample Data Button -->
        <button type="button" id="fill-sample-data" class="mb-4 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Fill with Sample Data</button>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.dentists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-semibold text-gray-700">First Name</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-semibold text-gray-700">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
            </div>

            <!-- Specialization -->
            <div>
                <label for="specialization" class="block text-sm font-semibold text-gray-700">Specialization</label>
                <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-semibold text-gray-700">Bio</label>
                <textarea name="bio" id="bio" rows="3"
                          class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('bio') }}</textarea>
            </div>

            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-sm font-semibold text-gray-700">Avatar (Max 5MB)</label>
                <input type="file" name="avatar" id="avatar" accept="image/*"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
            </div>

            <!-- Email Verified -->
            <div>
                <label for="email_verified" class="flex items-center">
                    <input type="checkbox" name="email_verified" id="email_verified" value="1"
                           {{ old('email_verified') ? 'checked' : '' }}
                           class="mr-2 border-gray-300 rounded focus:ring focus:ring-blue-200">
                    <span class="text-sm font-semibold text-gray-700">Email Verified</span>
                </label>
            </div>

            <!-- Phone Verified -->
            <div>
                <label for="phone_verified" class="flex items-center">
                    <input type="checkbox" name="phone_verified" id="phone_verified" value="1"
                           {{ old('phone_verified') ? 'checked' : '' }}
                           class="mr-2 border-gray-300 rounded focus:ring focus:ring-blue-200">
                    <span class="text-sm font-semibold text-gray-700">Phone Verified</span>
                </label>
            </div>

            <!-- Working Hours -->
            @php
                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            @endphp

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Working Hours</label>
                <div id="working-hours-container" class="space-y-4">
                    @foreach($daysOfWeek as $day)
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Day</label>
                                <input type="text" name="working_hours[{{ $day }}][day]" value="{{ $day }}"
                                       readonly class="w-full bg-gray-100 border rounded-md p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Start Time</label>
                                <input type="time" name="working_hours[{{ $day }}][start]" value="{{ old('working_hours.'.$day.'.start') }}"
                                       class="w-full border rounded-md p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">End Time</label>
                                <input type="time" name="working_hours[{{ $day }}][end]" value="{{ old('working_hours.'.$day.'.end') }}"
                                       class="w-full border rounded-md p-2">
                            </div>
                        </div>
                    @endforeach
                </div>
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
                        <!-- Off days will be added dynamically -->
                    </tbody>
                </table>

                <button type="button" id="add-off-day"
                        class="mt-3 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Add Off Day
                </button>
                <input type="hidden" name="off_days" id="off_days" value='[]'>
            </div>

            <!-- Hidden JSON Input -->
            <input type="hidden" name="working_hours_json" id="working_hours_json">

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.dentists.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Create Dentist
                </button>
            </div>
        </form>
    </div>

    <!-- JS Section -->
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const rows = document.querySelectorAll('#working-hours-container .grid');
            const workingHours = [];

            rows.forEach(row => {
                const day = row.querySelector('input[name*="[day]"]').value;
                const start = row.querySelector('input[name*="[start]"]').value;
                const end = row.querySelector('input[name*="[end]"]').value;
                if (start && end) {
                    workingHours.push({ day, start, end });
                }
            });

            document.getElementById('working_hours_json').value = JSON.stringify(workingHours);
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

        // Fill with Sample Data functionality
        document.getElementById('fill-sample-data').addEventListener('click', function() {
            // Text fields
            document.getElementById('first_name').value = 'John';
            document.getElementById('last_name').value = 'Doe';
            document.getElementById('email').value = 'john.doe@example.com';
            document.getElementById('phone').value = '123-456-7890';
            document.getElementById('specialization').value = 'Orthodontist';
            document.getElementById('bio').value = 'Experienced dentist specializing in orthodontics.';

            // Checkboxes
            document.getElementById('email_verified').checked = true;
            document.getElementById('phone_verified').checked = true;

            // Working hours
            const workingHoursSample = {
                'Monday':   { start: '09:00', end: '17:00' },
                'Tuesday':  { start: '09:00', end: '17:00' },
                'Wednesday':{ start: '09:00', end: '17:00' },
                'Thursday': { start: '09:00', end: '17:00' },
                'Friday':   { start: '09:00', end: '15:00' },
                'Saturday': { start: '',      end: ''      },
                'Sunday':   { start: '',      end: ''      }
            };
            Object.keys(workingHoursSample).forEach(day => {
                const startInput = document.querySelector(`input[name='working_hours[${day}][start]']`);
                const endInput = document.querySelector(`input[name='working_hours[${day}][end]']`);
                if (startInput && endInput) {
                    startInput.value = workingHoursSample[day].start;
                    endInput.value = workingHoursSample[day].end;
                }
            });

            // Off days
            const offDays = ['2025-05-20', '2025-06-15'];
            document.getElementById('off_days').value = JSON.stringify(offDays);
            const tbody = document.querySelector('#off-days-table tbody');
            tbody.innerHTML = '';
            offDays.forEach(date => {
                const newRow = document.createElement('tr');
                newRow.classList.add('border-t');
                newRow.innerHTML = `
                    <td class="px-4 py-2 border">${date}</td>
                    <td class="px-4 py-2 border text-center">
                        <button type="button" class="delete-off-day px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600" data-date="${date}">Delete</button>
                    </td>`;
                tbody.appendChild(newRow);
            });
        });
    </script>
@endsection