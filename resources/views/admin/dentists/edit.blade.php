@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4 text-blue-700">Edit Dentist</h2>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.dentists.update', $dentist['dentist_id']) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
            <input type="text" name="specialization" id="specialization" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $dentist['specialization'] }}" required>
        </div>

        <div>
            <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
            <textarea name="bio" id="bio" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $dentist['bio'] }}</textarea>
        </div>

        @php
            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $workingHours = collect($dentist['working_hours'] ?? []);
        @endphp

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Working Hours</label>
            <div id="working-hours-container" class="space-y-4">
                @foreach($daysOfWeek as $day)
                    @php
                        $dayData = $workingHours->firstWhere('day', $day);
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                        <div>
                            <label class="block text-sm font-medium mb-1">Day</label>
                            <input type="text" name="working_hours[{{ $day }}][day]" value="{{ $day }}" readonly class="w-full bg-gray-100 border rounded-md p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Start Time</label>
                            <input type="time" name="working_hours[{{ $day }}][start]" value="{{ $dayData['start'] ?? '' }}" class="w-full border rounded-md p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">End Time</label>
                            <input type="time" name="working_hours[{{ $day }}][end]" value="{{ $dayData['end'] ?? '' }}" class="w-full border rounded-md p-2">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <label for="off_days" class="block text-sm font-medium text-gray-700 mb-2">Off Days</label>
            <table id="off-days-table" class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Date</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($dentist['off_days'] ?? []) as $offDay)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $offDay }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button type="button" class="delete-off-day px-2 py-1 bg-red-600 text-white rounded-md hover:bg-red-700" data-date="{{ $offDay }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" id="add-off-day" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add Off Day</button>
            <input type="hidden" name="off_days" id="off_days" value='{{ json_encode($dentist['off_days'] ?? []) }}'>
        </div>

        <input type="hidden" name="working_hours_json" id="working_hours_json">

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Dentist</button>
        </div>
    </form>

    <!-- Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- JS Section -->
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const rows = document.querySelectorAll('#working-hours-container .grid');
            const workingHours = [];

            rows.forEach(row => {
                const day = row.querySelector('input[name*="[day]"]').value;
                const start = row.querySelector('input[name*="[start]"]').value;
                const end = row.querySelector('input[name*="[end]"]').value;

                // Only include if start and end times are set (not empty)
                if(start && end) {
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
                    <td class="border border-gray-300 px-4 py-2">${newDay}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button type="button" class="delete-off-day px-2 py-1 bg-red-600 text-white rounded-md hover:bg-red-700" data-date="${newDay}">Delete</button>
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
