@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4 text-blue-700">Edit Appointment</h2>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.appointments.update', $appointment['appointment_id']) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                <select name="patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Select a patient</option>
                    @foreach($patients_name as $patient)
                        <option value="{{ $patient['id'] }}" {{ $patient['id'] == $appointment['patient_id'] ? 'selected' : '' }}>
                            {{ $patient['first_name'].' '.$patient['last_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="dentist_id" class="block text-sm font-medium text-gray-700">Dentist</label>
                <select name="dentist_id" id="dentist_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Select a dentist</option>
                    @foreach($dentists_name as $dentist)
                        <option value="{{ $dentist['id'] }}" {{ $dentist['id'] == $appointment['patient_id'] ? 'selected' : '' }}>
                            {{ $dentist['first_name'].' '.$dentist['last_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="treatment_id" class="block text-sm font-medium text-gray-700">Treatment</label>
                <select name="treatment_id" id="treatment_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Select a treatment</option>
                    @foreach($treatments as $treatment)
                        <option value="{{ $treatment['treatment_id'] }}" {{ $treatment['treatment_id'] == $appointment['treatment_id'] ? 'selected' : '' }}>
                            {{ $treatment['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="Scheduled" {{ $appointment['status'] == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="Completed" {{ $appointment['status'] == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ $appointment['status'] == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="Rescheduled" {{ $appointment['status'] == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="datetime-local" name="start_time" id="start_time" 
                       value="{{ date('Y-m-d\TH:i', strtotime($appointment['start_time'])) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="datetime-local" name="end_time" id="end_time"
                       value="{{ date('Y-m-d\TH:i', strtotime($appointment['end_time'])) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $appointment['notes'] }}</textarea>
        </div>

        <div id="cancellation_reason_div" style="display: {{ $appointment['status'] == 'Cancelled' ? 'block' : 'none' }};">
            <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Cancellation Reason</label>
            <textarea name="cancellation_reason" id="cancellation_reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $appointment['cancellation_reason'] }}</textarea>
        </div>

        <div id="reschedule_reason_div" style="display: {{ $appointment['status'] == 'Rescheduled' ? 'block' : 'none' }};">
            <label for="reschedule_reason" class="block text-sm font-medium text-gray-700">Reschedule Reason</label>
            <textarea name="reschedule_reason" id="reschedule_reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $appointment['reschedule_reason'] }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Appointment</button>
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
@endsection