@extends('layouts.admin')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-blue-800 mb-6 border-b pb-2">Edit Appointment</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.appointments.update', $appointment['appointment_id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="patient_id" class="block text-sm font-semibold text-gray-700">Patient</label>
                    <select name="patient_id" id="patient_id" class="mt-2 w-full rounded-lg border-gray-300 shadow-sm" required>
                        <option value="">Select a patient</option>
                        @foreach($patients_name as $patient)
                            <option value="{{ $patient['id'] }}" {{ $patient['id'] == $appointment['patient_id'] ? 'selected' : '' }}>
                                {{ $patient['first_name'].' '.$patient['last_name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="dentist_id" class="block text-sm font-semibold text-gray-700">Dentist</label>
                    <select name="dentist_id" id="dentist_id" class="mt-2 w-full rounded-lg border-gray-300 shadow-sm" required>
                        <option value="">Select a dentist</option>
                        @foreach($dentists_name as $dentist)
                            <option value="{{ $dentist['id'] }}" {{ $dentist['id'] == $appointment['dentist_id'] ? 'selected' : '' }}>
                                {{ $dentist['first_name'].' '.$dentist['last_name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="treatment_id" class="block text-sm font-semibold text-gray-700">Treatment</label>
                    <select name="treatment_id" id="treatment_id" class="mt-2 w-full rounded-lg border-gray-300 shadow-sm" required>
                        <option value="">Select a treatment</option>
                        {{-- Treatment options should be inserted here --}}
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-2 w-full rounded-lg border-gray-300 shadow-sm" required>
                        <option value="Scheduled" {{ $appointment['status'] == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="Completed" {{ $appointment['status'] == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ $appointment['status'] == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="Rescheduled" {{ $appointment['status'] == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_time" class="block text-sm font-semibold text-gray-700">Start Time</label>
                    <input type="datetime-local" name="start_time" id="start_time"
                           value="{{ date('Y-m-d\TH:i', strtotime($appointment['start_time'])) }}"
                           class="mt-2 w-full rounded-lg border-gray-300 shadow-sm" required>
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-semibold text-gray-700">End Time</label>
                    <input type="datetime-local" name="end_time" id="end_time"
                           value="{{ date('Y-m-d\TH:i', strtotime($appointment['end_time'])) }}"
                           class="mt-2 w-full rounded-lg border-gray-300 shadow-sm" required>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-semibold text-gray-700">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-2 w-full rounded-lg border-gray-300 shadow-sm">{{ $appointment['notes'] }}</textarea>
            </div>

            <div id="cancellation_reason_div" class="{{ $appointment['status'] == 'Cancelled' ? '' : 'hidden' }}">
                <label for="cancellation_reason" class="block text-sm font-semibold text-gray-700">Cancellation Reason</label>
                <textarea name="cancellation_reason" id="cancellation_reason" rows="3"
                          class="mt-2 w-full rounded-lg border-gray-300 shadow-sm">{{ $appointment['cancellation_reason'] }}</textarea>
            </div>

            <div id="reschedule_reason_div" class="{{ $appointment['status'] == 'Rescheduled' ? '' : 'hidden' }}">
                <label for="reschedule_reason" class="block text-sm font-semibold text-gray-700">Reschedule Reason</label>
                <textarea name="reschedule_reason" id="reschedule_reason" rows="3"
                          class="mt-2 w-full rounded-lg border-gray-300 shadow-sm">{{ $appointment['reschedule_reason'] }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Update Appointment
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('status').addEventListener('change', function () {
            const cancellationDiv = document.getElementById('cancellation_reason_div');
            const rescheduleDiv = document.getElementById('reschedule_reason_div');

            cancellationDiv.classList.add('hidden');
            rescheduleDiv.classList.add('hidden');

            if (this.value === 'Cancelled') {
                cancellationDiv.classList.remove('hidden');
            } else if (this.value === 'Rescheduled') {
                rescheduleDiv.classList.remove('hidden');
            }
        });
    </script>
@endsection
