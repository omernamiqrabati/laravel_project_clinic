@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Edit Dentist Profile</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.dentists.update', $dentist['dentist_id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Specialization -->
            <div>
                <label for="specialization" class="block text-sm font-semibold text-gray-700">Specialization</label>
                <input type="text" name="specialization" id="specialization" value="{{ $dentist['specialization'] }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-semibold text-gray-700">Bio</label>
                <textarea name="bio" id="bio" rows="3"
                          class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ $dentist['bio'] }}</textarea>
            </div>

            <!-- Working Hours -->
            @php
                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $workingHours = collect($dentist['working_hours'] ?? []);
            @endphp

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Working Hours</label>
                <div id="working-hours-container" class="space-y-4">
                    @foreach($daysOfWeek as $day)
                        @php
                            $dayData = $workingHours->firstWhere('day', $day);
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Day</label>
                                <input type="text" name="working_hours[{{ $day }}][day]" value="{{ $day }}"
                                       readonly class="w-full bg-gray-100 border rounded-md p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Start Time</label>
                                <input type="time" name="working_hours[{{ $day }}][start]" value="{{ $dayData['start'] ?? '' }}"
                                       class="w-full border rounded-md p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">End Time</label>
                                <input type="time" name="working_hours[{{ $day }}][end]" value="{{ $dayData['end'] ?? '' }}"
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
            <input type="hidden" name="working_hours_json" id="working_hours_json">

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Update Dentist
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
    </script>
@endsection
