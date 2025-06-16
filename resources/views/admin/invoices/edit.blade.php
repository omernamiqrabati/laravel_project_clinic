@extends('layouts.admin')

@section('title', 'Edit Invoice')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
            <h1 class="text-3xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    ✏️ Edit Invoice
                </span>
            </h1>
            <a href="{{ route('admin.invoices.index') }}" 
               class="px-4 py-2 text-gray-600 hover:text-white hover:bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl transition-all duration-300 border-2 border-gray-200 hover:border-transparent">
                ← Back to Invoices
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 shadow-sm">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <h4 class="font-semibold">Please fix the following errors:</h4>
                </div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.invoices.update', $invoice['invoice_id']) }}" method="POST" class="space-y-8">
            @csrf
            @method('PATCH')

            {{-- Patient & Appointment Information --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></div>
                    Patient & Appointment Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Selection -->
                    <div class="space-y-2">
                        <label for="patient_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Patient *
                        </label>
                        <select name="patient_id" id="patient_id" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white">
                            <option value="">Select a patient...</option>
                            @if(isset($patients))
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->patient_id }}" 
                                            {{ $invoice['patient_id'] === $patient->patient_id ? 'selected' : '' }}
                                            data-name="{{ $patient->patient_name }}"
                                            data-phone="{{ $patient->phone }}"
                                            data-email="{{ $patient->email }}"
                                            data-address="{{ $patient->address }}">
                                        {{ $patient->patient_name }} 
                                        @if($patient->phone)
                                            - {{ $patient->phone }}
                                        @endif
                                        @if($patient->email)
                                            - {{ $patient->email }}
                                        @endif
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Appointment Selection (Optional) -->
                    <div class="space-y-2">
                        <label for="appointment_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Appointment (Optional)
                        </label>
                        <select name="appointment_id" id="appointment_id" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white">
                            <option value="">No appointment selected</option>
                            @if(isset($appointments))
                                @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->appointment_id }}" 
                                            {{ $invoice['appointment_id'] === $appointment->appointment_id ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('M d, Y g:i A') }} 
                                        - {{ $appointment->patient_name }}
                                        @if($appointment->treatment_name)
                                            ({{ $appointment->treatment_name }})
                                        @endif
                                    </option>
                                @endforeach
                            @endif
                        </select>
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
                            <input type="number" 
                                   name="total_amount" 
                                   id="total_amount" 
                                   value="{{ $invoice['total_amount'] }}"
                                   step="0.01" 
                                   min="0" 
                                   class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400" 
                                   required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="tax" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tax Amount
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-lg">$</span>
                            </div>
                            <input type="number" 
                                   name="tax" 
                                   id="tax" 
                                   value="{{ $invoice['tax'] ?? 0 }}"
                                   step="0.01" 
                                   min="0" 
                                   class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400">
                        </div>
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
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white" 
                                required>
                            <option value="unpaid" {{ strtolower($invoice['payment_status']) === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paying" {{ strtolower($invoice['payment_status']) === 'paying' ? 'selected' : '' }}>Paying</option>
                            <option value="paid" {{ strtolower($invoice['payment_status']) === 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 resize-none"
                                  placeholder="Additional notes or comments...">{{ $invoice['notes'] ?? '' }}</textarea>
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
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Update Invoice
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
        
        <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl border border-orange-100 p-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">Invoice Details</h4>
                        <p class="text-sm text-gray-600">ID: {{ substr($invoice['invoice_id'], 0, 8) }}...</p>
                        <p class="text-sm text-gray-600">Created: {{ \Carbon\Carbon::parse($invoice['creation_date'])->format('M d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <div id="preview-status" class="px-3 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            {{ ucfirst($invoice['payment_status']) }}
                        </div>
                    </div>
                </div>
                
                <hr class="border-gray-200">
                
                <div id="preview-patient" class="text-sm">
                    <strong class="text-gray-800">Patient:</strong> 
                    <span class="text-gray-600">Select a patient to see details</span>
                </div>
                
                <hr class="border-gray-200">
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Subtotal:</span>
                        <span id="preview-subtotal" class="font-medium text-gray-900">${{ number_format($invoice['total_amount'] - ($invoice['tax'] ?? 0), 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Tax:</span>
                        <span id="preview-tax" class="font-medium text-gray-900">${{ number_format($invoice['tax'] ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center font-bold text-lg border-t-2 border-gray-300 pt-3">
                        <span class="text-gray-800">Total:</span>
                        <span id="preview-total" class="text-blue-600">${{ number_format($invoice['total_amount'], 2) }}</span>
                    </div>
                </div>
                
                <div id="preview-notes" class="text-sm text-gray-600 mt-4 p-3 bg-gray-50 rounded-lg">
                    @if($invoice['notes'])
                        <strong class="text-gray-800">Notes:</strong> {{ $invoice['notes'] }}
                    @else
                        <em class="text-gray-500">No notes added</em>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const patientSelect = document.getElementById('patient_id');
    const totalAmountInput = document.getElementById('total_amount');
    const taxInput = document.getElementById('tax');
    const paymentStatusSelect = document.getElementById('payment_status');
    const notesInput = document.getElementById('notes');
    
    // Update preview when form changes
    function updatePreview() {
        // Update patient info
        const selectedOption = patientSelect.options[patientSelect.selectedIndex];
        const previewPatient = document.getElementById('preview-patient');
        
        if (selectedOption.value) {
            const name = selectedOption.dataset.name || 'Unknown';
            const phone = selectedOption.dataset.phone || '';
            const email = selectedOption.dataset.email || '';
            
            let patientInfo = `<strong class="text-gray-800">Patient:</strong> ${name}`;
            if (phone) patientInfo += `<br><small class="text-gray-600">Phone: ${phone}</small>`;
            if (email) patientInfo += `<br><small class="text-gray-600">Email: ${email}</small>`;
            
            previewPatient.innerHTML = patientInfo;
        } else {
            previewPatient.innerHTML = '<strong class="text-gray-800">Patient:</strong> <span class="text-gray-600">Select a patient to see details</span>';
        }
        
        // Update amounts
        const totalAmount = parseFloat(totalAmountInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;
        const subtotal = totalAmount - tax;
        
        document.getElementById('preview-subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('preview-tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('preview-total').textContent = `$${totalAmount.toFixed(2)}`;
        
        // Update payment status
        const statusElement = document.getElementById('preview-status');
        const status = paymentStatusSelect.value;
        const statusColors = {
            'paid': 'bg-green-100 text-green-800',
            'paying': 'bg-yellow-100 text-yellow-800',
            'unpaid': 'bg-red-100 text-red-800'
        };
        
        statusElement.className = `px-3 py-2 text-sm font-semibold rounded-full ${statusColors[status] || 'bg-gray-100 text-gray-800'}`;
        statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        
        // Update notes
        const previewNotes = document.getElementById('preview-notes');
        if (notesInput.value.trim()) {
            previewNotes.innerHTML = `<strong class="text-gray-800">Notes:</strong> ${notesInput.value}`;
            previewNotes.classList.remove('hidden');
        } else {
            previewNotes.innerHTML = '<em class="text-gray-500">No notes added</em>';
        }
    }
    
    // Add event listeners
    patientSelect.addEventListener('change', updatePreview);
    totalAmountInput.addEventListener('input', updatePreview);
    taxInput.addEventListener('input', updatePreview);
    paymentStatusSelect.addEventListener('change', updatePreview);
    notesInput.addEventListener('input', updatePreview);
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection
