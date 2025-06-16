@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
            <div class="flex justify-between items-center mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
                <h2 class="text-3xl font-bold text-gray-800">
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        🦷 Edit Appointment
                    </span>
                </h2>
                <a href="{{ route('admin.appointments.index') }}" 
                   class="px-4 py-2 text-gray-600 hover:text-white hover:bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl transition-all duration-300 border-2 border-gray-200 hover:border-transparent">
                    ← Back to Appointments
                </a>
            </div>

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

            <form action="{{ route('admin.appointments.update', $appointment['appointment_id']) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Appointment Details --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></div>
                        Appointment Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="patient_id" class="block text-sm font-semibold text-gray-700 mb-2">Patient *</label>
                            <select name="patient_id" id="patient_id" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white @error('patient_id') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                                <option value="" disabled>Select a patient</option>
                                @foreach($patients_name as $patient)
                                    <option value="{{ $patient['id'] }}" {{ $patient['id'] == $appointment['patient_id'] ? 'selected' : '' }}>
                                        {{ $patient['first_name'].' '.$patient['last_name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="dentist_id" class="block text-sm font-semibold text-gray-700 mb-2">Dentist *</label>
                            <select name="dentist_id" id="dentist_id" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white @error('dentist_id') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                                <option value="" disabled>Select a dentist</option>
                                @foreach($dentists_name as $dentist)
                                    <option value="{{ $dentist['id'] }}" {{ $dentist['id'] == $appointment['dentist_id'] ? 'selected' : '' }}>
                                        {{ $dentist['first_name'].' '.$dentist['last_name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dentist_id')
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

                {{-- Treatment & Status --}}
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-teal-500 rounded-full mr-3"></div>
                        Treatment & Status
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="treatment_id" class="block text-sm font-semibold text-gray-700 mb-2">Treatment *</label>
                            <select name="treatment_id" id="treatment_id" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white @error('treatment_id') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                                <option value="" disabled>Select a treatment</option>
                                @foreach($treatments as $treatment)
                                    <option value="{{ $treatment['treatment_id'] }}" {{ $treatment['treatment_id'] == $appointment['treatment_id'] ? 'selected' : '' }}>
                                        {{ $treatment['name'] }} ({{ currency_format($treatment['cost']) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('treatment_id')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white @error('status') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                                <option value="" disabled>Select status</option>
                                <option value="Scheduled" {{ $appointment['status'] == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="Completed" {{ $appointment['status'] == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ $appointment['status'] == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Rescheduled" {{ $appointment['status'] == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                            </select>
                            @error('status')
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

                {{-- Schedule Information --}}
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-3"></div>
                        Schedule Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">Start Time *</label>
                            <input type="datetime-local" name="start_time" id="start_time"
                                   value="{{ date('Y-m-d\TH:i', strtotime($appointment['start_time'])) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white @error('start_time') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                            @error('start_time')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">End Time *</label>
                            <input type="datetime-local" name="end_time" id="end_time"
                                   value="{{ date('Y-m-d\TH:i', strtotime($appointment['end_time'])) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white @error('end_time') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                            @error('end_time')
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

                {{-- Additional Information --}}
                <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-2xl border border-orange-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-red-500 rounded-full mr-3"></div>
                        Additional Information
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" id="notes" rows="4" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 resize-none @error('notes') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                      placeholder="Add any additional notes about the appointment...">{{ $appointment['notes'] }}</textarea>
                            @error('notes')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div id="cancellation_reason_div" class="space-y-2" style="display: {{ $appointment['status'] == 'Cancelled' ? 'block' : 'none' }};">
                            <label for="cancellation_reason" class="block text-sm font-semibold text-gray-700 mb-2">Cancellation Reason</label>
                            <textarea name="cancellation_reason" id="cancellation_reason" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-red-200 rounded-xl shadow-sm focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all duration-300 hover:border-red-300 bg-red-50 placeholder-red-400 resize-none" 
                                      placeholder="Please provide reason for cancellation...">{{ $appointment['cancellation_reason'] }}</textarea>
                        </div>

                        <div id="reschedule_reason_div" class="space-y-2" style="display: {{ $appointment['status'] == 'Rescheduled' ? 'block' : 'none' }};">
                            <label for="reschedule_reason" class="block text-sm font-semibold text-gray-700 mb-2">Reschedule Reason</label>
                            <textarea name="reschedule_reason" id="reschedule_reason" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-yellow-200 rounded-xl shadow-sm focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 transition-all duration-300 hover:border-yellow-300 bg-yellow-50 placeholder-yellow-400 resize-none" 
                                      placeholder="Please provide reason for rescheduling...">{{ $appointment['reschedule_reason'] }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-between pt-6">
                    <a href="{{ route('admin.appointments.index') }}" 
                       class="group relative px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center">
                            <svg class="h-5 w-5 mr-3 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Cancel
                        </div>
                    </a>
                    
                    <button type="submit" 
                            class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative flex items-center">
                            <svg class="h-5 w-5 mr-3 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Update Appointment
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
