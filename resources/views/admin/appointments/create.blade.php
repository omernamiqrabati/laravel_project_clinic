@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    🦷 Create New Appointment
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

            <form action="{{ route('admin.appointments.store') }}" method="POST" class="space-y-8">
                @csrf

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
                                <option value="" disabled selected>Select a patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient['patient_id'] }}">{{ $patient['patient_id'] }}</option>
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
                                <option value="" disabled selected>Select a dentist</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist['dentist_id'] }}">{{ $dentist['specialization'] }}</option>
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
                                <option value="" disabled selected>Select a treatment</option>
                                @foreach($treatments as $treatment)
                                    <option value="{{ $treatment['treatment_id'] }}">{{ $treatment['name'] }} ({{ currency_format($treatment['cost']) }})</option>
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
                                <option value="" disabled selected>Select status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
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
                                      placeholder="Add any additional notes about the appointment..."></textarea>
                            @error('notes')
                                <p class="text-red-600 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div id="cancellation_reason_div" class="space-y-2" style="display: none;">
                            <label for="cancellation_reason" class="block text-sm font-semibold text-gray-700 mb-2">Cancellation Reason</label>
                            <textarea name="cancellation_reason" id="cancellation_reason" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-red-200 rounded-xl shadow-sm focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all duration-300 hover:border-red-300 bg-red-50 placeholder-red-400 resize-none" 
                                      placeholder="Please provide reason for cancellation..."></textarea>
                        </div>

                        <div id="reschedule_reason_div" class="space-y-2" style="display: none;">
                            <label for="reschedule_reason" class="block text-sm font-semibold text-gray-700 mb-2">Reschedule Reason</label>
                            <textarea name="reschedule_reason" id="reschedule_reason" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-yellow-200 rounded-xl shadow-sm focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 transition-all duration-300 hover:border-yellow-300 bg-yellow-50 placeholder-yellow-400 resize-none" 
                                      placeholder="Please provide reason for rescheduling..."></textarea>
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
                                <path d="M12 4v16m8-8H4" />
                            </svg>
                            Create Appointment
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