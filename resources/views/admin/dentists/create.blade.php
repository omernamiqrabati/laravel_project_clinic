@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-2xl font-semibold text-blue-800 mb-6 border-b pb-2">🦷 Add New Dentist</h2>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.dentists.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Specialization --}}
        <div>
            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
            <input type="text" name="specialization" id="specialization" required
                class="w-full border border-gray-300 rounded-md p-3 focus:ring focus:ring-blue-200"
                value="{{ old('specialization') }}">
        </div>

        {{-- Bio --}}
        <div>
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
            <textarea name="bio" id="bio" rows="4"
                class="w-full border border-gray-300 rounded-md p-3 focus:ring focus:ring-blue-200">{{ old('bio') }}</textarea>
        </div>

        {{-- Working Hours --}}
        @php $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; @endphp

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Working Hours</label>
            <div id="working-hours-container" class="space-y-4">
                @foreach($daysOfWeek as $day)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Day</label>
                            <input type="text" name="working_hours[{{ $day }}][day]" value="{{ $day }}" readonly
                                class="w-full bg-gray-100 border rounded-md p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Start Time</label>
                            <input type="time" name="working_hours[{{ $day }}][start]" class="w-full border rounded-md p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">End Time</label>
                            <input type="time" name="working_hours[{{ $day }}][end]" class="w-full border rounded-md p-2 text-sm">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Off Days --}}
        <div>
            <label for="off_days" class="block text-sm font-medium text-gray-700 mb-2">Off Days</label>
            <table class="table-auto w-full text-sm border border-gray-300 mb-2">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="border px-4 py-2 text-left">Date</th>
                        <th class="border px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody id="off-days-table-body">
                    <!-- Off days will be injected here -->
                </tbody>
            </table>
            <button type="button" id="add-off-day" class="mt-1 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">+ Add Off Day</button>
            <input type="hidden" name="off_days" id="off_days" value='[]'>
        </div>

        <input type="hidden" name="working_hours_json" id="working_hours_json">

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Dentist</button>
        </div>
    </form>
</div>

<!-- JS Section -->
<script>
    // Convert working hours to JSON on submit
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

    // Off Day Add Button
    document.getElementById('add-off-day').addEventListener('click', function () {
        const offDaysInput = document.getElementById('off_days');
        const tbody = document.getElementById('off-days-table-body');
        let offDays = JSON.parse(offDaysInput.value || '[]');

        const newDate = prompt('Enter a new off day (e.g., 2025-06-01):', new Date().toISOString().split('T')[0]);
        if (newDate && !offDays.includes(newDate)) {
            offDays.push(newDate);
            offDaysInput.value = JSON.stringify(offDays);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border px-4 py-2">${newDate}</td>
                <td class="border px-4 py-2">
                    <button type="button" class="delete-off-day px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" data-date="${newDate}">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        }
    });

    // Delete Off Day Handler
    document.getElementById('off-days-table-body').addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-off-day')) {
            const date = e.target.dataset.date;
            let offDays = JSON.parse(document.getElementById('off_days').value || '[]');
            offDays = offDays.filter(d => d !== date);
            document.getElementById('off_days').value = JSON.stringify(offDays);
            e.target.closest('tr').remove();
        }
    });
</script>
@endsection
