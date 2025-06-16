@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
            <h1 class="text-3xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    🧾 Create New Invoice
                </span>
            </h1>
            <a href="{{ route('admin.invoices.index') }}" 
               class="px-4 py-2 text-gray-600 hover:text-white hover:bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl transition-all duration-300 border-2 border-gray-200 hover:border-transparent">
                ← Back to Invoices
            </a>
        </div>

        <form action="{{ route('admin.invoices.store') }}" method="POST" class="space-y-8">
            @csrf
            
            {{-- Patient & Appointment Information --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></div>
                    Patient & Appointment Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Selection -->
                    <div class="space-y-2">
                        <label for="patient_id" class="block text-sm font-semibold text-gray-700 mb-2">Patient *</label>
                        <select name="patient_id" id="patient_id" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white @error('patient_id') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                            <option value="" disabled selected>Select a patient</option>
                            @if(isset($patients) && count($patients) > 0)
                                @foreach($patients as $patient)
                                    @php
                                        $patient_name = $patient->patient_name ?? 'Unknown Patient';
                                        $phone_display = !empty($patient->phone) ? ' - ' . $patient->phone : '';
                                        $email_display = !empty($patient->email) ? ' (' . $patient->email . ')' : '';
                                    @endphp
                                    <option value="{{ $patient->patient_id }}" 
                                            data-name="{{ $patient_name }}"
                                            data-phone="{{ $patient->phone ?? '' }}"
                                            data-email="{{ $patient->email ?? '' }}"
                                            data-address="{{ $patient->address ?? '' }}">
                                        {{ $patient_name }}{{ $phone_display }}{{ $email_display }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No patients available</option>
                            @endif
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

                    <!-- Appointment Selection -->
                    <div class="space-y-2">
                        <label for="appointment_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Appointment (Optional)
                        </label>
                        <select name="appointment_id" id="appointment_id" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white @error('appointment_id') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror">
                            <option value="">Select an appointment</option>
                            @if(isset($appointments) && count($appointments) > 0)
                                @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->appointment_id }}">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('M d, Y - H:i') }}
                                        @if(!empty($appointment->treatment_name))
                                            - {{ $appointment->treatment_name }}
                                        @endif
                                        @if(!empty($appointment->patient_name))
                                            ({{ $appointment->patient_name }})
                                        @endif
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No appointments available</option>
                            @endif
                        </select>
                        @error('appointment_id')
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

            {{-- Financial Information --}}
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-teal-500 rounded-full mr-3"></div>
                    Financial Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="total_amount" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-lg">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="total_amount" id="total_amount" 
                                   class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('total_amount') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                   placeholder="0.00" required>
                        </div>
                        @error('total_amount')
                            <p class="text-red-600 text-sm flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="tax" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tax Amount
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-lg">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="tax" id="tax" 
                                   class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('tax') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                   placeholder="0.00" value="0">
                        </div>
                        @error('tax')
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

            {{-- Payment & Additional Information --}}
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-3"></div>
                    Payment & Additional Information
                </h3>
                <div class="space-y-6">
                    <!-- Payment Status -->
                    <div class="space-y-2">
                        <label for="payment_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Payment Status <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_status" id="payment_status" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white @error('payment_status') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                            <option value="unpaid" selected>Unpaid</option>
                            <option value="paying">Paying</option>
                            <option value="paid">Paid</option>
                        </select>
                        @error('payment_status')
                            <p class="text-red-600 text-sm flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 resize-none @error('notes') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" 
                                  placeholder="Add any additional notes about this invoice..."></textarea>
                        @error('notes')
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

            <!-- Form Actions -->
            <div class="flex justify-between pt-6">
                <a href="{{ route('admin.invoices.index') }}" 
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
                        Create Invoice
                    </div>
                </button>
            </div>
        </form>
    </div>

    <!-- Invoice Preview -->
    <div class="mt-8 bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-red-500 rounded-full mr-3"></div>
            Invoice Preview
        </h3>
        <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl border border-orange-100 p-6" id="invoice-preview">
            <div class="text-center text-gray-500 py-8" id="preview-placeholder">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg font-medium">Fill out the form above to see invoice preview</p>
            </div>
            
            <!-- Preview Content (hidden by default) -->
            <div id="preview-content" class="hidden bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">INVOICE</h2>
                        <p class="text-sm text-gray-600">Invoice #<span id="preview-invoice-id">INV-{{ date('Y-m-d') }}-{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}</span></p>
                        <p class="text-sm text-gray-600">Date: <span id="preview-date">{{ date('M d, Y') }}</span></p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-lg font-semibold text-gray-900">Dental Clinic</h3>
                        <p class="text-sm text-gray-600">123 Main Street</p>
                        <p class="text-sm text-gray-600">City, State 12345</p>
                        <p class="text-sm text-gray-600">Phone: (555) 123-4567</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Bill To:</h4>
                    <div id="preview-patient-info" class="text-sm text-gray-600">
                        <p>Patient information will appear here</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 text-sm font-medium text-gray-900">Description</th>
                                <th class="text-right py-2 text-sm font-medium text-gray-900">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-sm text-gray-600" id="preview-service">Medical/Dental Services</td>
                                <td class="py-3 text-sm text-gray-900 text-right">$<span id="preview-subtotal">0.00</span></td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-sm text-gray-600">Tax</td>
                                <td class="py-3 text-sm text-gray-900 text-right">$<span id="preview-tax-amount">0.00</span></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-900">
                                <td class="py-3 text-sm font-semibold text-gray-900">Total</td>
                                <td class="py-3 text-lg font-bold text-gray-900 text-right">$<span id="preview-total">0.00</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Payment Status:</span>
                        <span id="preview-status" class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Unpaid</span>
                    </div>
                    <div id="preview-notes-section" class="mt-4 hidden">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Notes:</h5>
                        <p id="preview-notes" class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const patientSelect = document.getElementById('patient_id');
    const totalAmountInput = document.getElementById('total_amount');
    const taxInput = document.getElementById('tax');
    const paymentStatusSelect = document.getElementById('payment_status');
    const notesInput = document.getElementById('notes');
    
    // Preview elements
    const previewPlaceholder = document.getElementById('preview-placeholder');
    const previewContent = document.getElementById('preview-content');
    const previewPatientInfo = document.getElementById('preview-patient-info');
    const previewSubtotal = document.getElementById('preview-subtotal');
    const previewTaxAmount = document.getElementById('preview-tax-amount');
    const previewTotal = document.getElementById('preview-total');
    const previewStatus = document.getElementById('preview-status');
    const previewNotes = document.getElementById('preview-notes');
    const previewNotesSection = document.getElementById('preview-notes-section');
    
    function updatePreview() {
        const totalAmount = parseFloat(totalAmountInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;
        const selectedPatient = patientSelect.options[patientSelect.selectedIndex];
        const paymentStatus = paymentStatusSelect.value;
        const notes = notesInput.value.trim();
        
        // Show preview if there's meaningful data
        if (totalAmount > 0 || selectedPatient.value) {
            previewPlaceholder.classList.add('hidden');
            previewContent.classList.remove('hidden');
            
            // Update patient info
            if (selectedPatient.value) {
                const patientName = selectedPatient.getAttribute('data-name') || 'Unknown Patient';
                const patientPhone = selectedPatient.getAttribute('data-phone');
                const patientEmail = selectedPatient.getAttribute('data-email');
                const patientAddress = selectedPatient.getAttribute('data-address');
                
                let patientInfo = `<p class="font-medium text-gray-900">${patientName}</p>`;
                if (patientPhone) {
                    patientInfo += `<p class="text-sm text-gray-600">Phone: ${patientPhone}</p>`;
                }
                if (patientEmail) {
                    patientInfo += `<p class="text-sm text-gray-600">Email: ${patientEmail}</p>`;
                }
                if (patientAddress) {
                    patientInfo += `<p class="text-sm text-gray-600">Address: ${patientAddress}</p>`;
                }
                
                previewPatientInfo.innerHTML = patientInfo;
            } else {
                previewPatientInfo.innerHTML = '<p class="text-gray-500 italic">No patient selected</p>';
            }
            
            // Calculate subtotal (total amount minus tax)
            const subtotal = Math.max(0, totalAmount - tax);
            
            // Update amounts
            previewSubtotal.textContent = subtotal.toFixed(2);
            previewTaxAmount.textContent = tax.toFixed(2);
            previewTotal.textContent = totalAmount.toFixed(2);
            
            // Update payment status
            const statusClasses = {
                'unpaid': 'bg-red-100 text-red-800',
                'paying': 'bg-yellow-100 text-yellow-800',
                'paid': 'bg-green-100 text-green-800'
            };
            
            previewStatus.className = `px-3 py-1 rounded-full text-sm font-medium ${statusClasses[paymentStatus] || statusClasses.unpaid}`;
            previewStatus.textContent = paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1);
            
            // Update notes
            if (notes) {
                previewNotesSection.classList.remove('hidden');
                previewNotes.textContent = notes;
            } else {
                previewNotesSection.classList.add('hidden');
            }
        } else {
            previewPlaceholder.classList.remove('hidden');
            previewContent.classList.add('hidden');
        }
    }
    
    // Add event listeners
    [patientSelect, totalAmountInput, taxInput, paymentStatusSelect, notesInput].forEach(element => {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });
    
    // Auto-calculate tax as percentage if needed (optional feature)
    totalAmountInput.addEventListener('input', function() {
        // If tax is 0 and total amount is entered, you could auto-calculate tax
        // This is optional and depends on your business logic
        if (parseFloat(taxInput.value) === 0 && parseFloat(this.value) > 0) {
            // Example: 10% tax rate (uncomment if needed)
            // taxInput.value = (parseFloat(this.value) * 0.10).toFixed(2);
        }
        updatePreview();
    });
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection
