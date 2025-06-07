@extends('layouts.admin')

@section('content')
    <h2 class="text-3xl font-bold mb-6 text-blue-800">🦷 Create New Appointment</h2>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.appointments.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                <select name="patient_id" id="patient_id" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2" required>
                    <option value="" disabled selected>Select a patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient['patient_id'] }}">{{ $patient['patient_id'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="dentist_id" class="block text-sm font-medium text-gray-700">Dentist</label>
                <select name="dentist_id" id="dentist_id" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2" required>
                    <option value="" disabled selected>Select a dentist</option>
                    @foreach($dentists as $dentist)
                        <option value="{{ $dentist['dentist_id'] }}">{{ $dentist['specialization'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="treatment_id" class="block text-sm font-medium text-gray-700">Treatment</label>
                <select name="treatment_id" id="treatment_id" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2" required>
                    <option value="" disabled selected>Select a treatment</option>
                    @foreach($treatments as $treatment)
                        <option value="{{ $treatment['treatment_id'] }}">{{ $treatment['name'] }} ({{ currency_format($treatment['cost']) }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2" required>
                    <option value="" disabled selected>Select status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="datetime-local" name="start_time" id="start_time" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2" required>
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="datetime-local" name="end_time" id="end_time" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2" required>
            </div>
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2"></textarea>
        </div>

        <div id="cancellation_reason_div" style="display: none;">
            <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Cancellation Reason</label>
            <textarea name="cancellation_reason" id="cancellation_reason" rows="3" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 px-4 py-2"></textarea>
        </div>

        <div id="reschedule_reason_div" style="display: none;">
            <label for="reschedule_reason" class="block text-sm font-medium text-gray-700">Reschedule Reason</label>
            <textarea name="reschedule_reason" id="reschedule_reason" rows="3" class="mt-1 block w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 px-4 py-2"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-xl font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-shadow">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Appointment
            </button>
        </div>
    </form>

    <script>
        document.getElementById('status').addEventListener('change', function() {
            const cancellationDiv = document.getElementById('cancellation_reason_div');
            const rescheduleDiv = document.getElementById('reschedule_reason_div');

            if (this.value === 'Cancelled') {
                cancellationDiv.style.display = 'block';
                rescheduleDiv.style.display = 'none';
            } else if (this.value === 'Rescheduled') {
                cancellationDiv.style.display = 'none';
                rescheduleDiv.style.display = 'block';
            } else {
                cancellationDiv.style.display = 'none';
                rescheduleDiv.style.display = 'none';
            }
        });
    </script>

    @php
        function currency_format($amount) {
            return '$' . number_format($amount, 2);
        }
    @endphp
@endsection