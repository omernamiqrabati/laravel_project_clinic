@extends('layouts.admin')

@section('title', 'Edit Invoice')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-800">Edit Invoice</h1>
                <a href="{{ route('admin.invoices.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Back to Invoices
                </a>
            </div>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.invoices.update', $invoice['invoice_id']) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @csrf
                @method('PATCH')

                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Patient Selection -->
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Patient
                        </label>
                        <select name="patient_id" id="patient_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                    <div>
                        <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Appointment (Optional)
                        </label>
                        <select name="appointment_id" id="appointment_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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

                    <!-- Financial Details -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Total Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" 
                                       name="total_amount" 
                                       id="total_amount" 
                                       value="{{ $invoice['total_amount'] }}"
                                       step="0.01" 
                                       min="0" 
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       required>
                            </div>
                        </div>

                        <div>
                            <label for="tax" class="block text-sm font-medium text-gray-700 mb-2">
                                Tax Amount
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" 
                                       name="tax" 
                                       id="tax" 
                                       value="{{ $invoice['tax'] ?? 0 }}"
                                       step="0.01" 
                                       min="0" 
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Status <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_status" id="payment_status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="unpaid" {{ strtolower($invoice['payment_status']) === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paying" {{ strtolower($invoice['payment_status']) === 'paying' ? 'selected' : '' }}>Paying</option>
                            <option value="paid" {{ strtolower($invoice['payment_status']) === 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Additional notes or comments...">{{ $invoice['notes'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Right Column - Invoice Preview -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Invoice Preview</h3>
                    
                    <div class="bg-white p-4 rounded border space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-gray-800">Invoice Details</h4>
                                <p class="text-sm text-gray-600">ID: {{ substr($invoice['invoice_id'], 0, 8) }}...</p>
                                <p class="text-sm text-gray-600">Created: {{ \Carbon\Carbon::parse($invoice['creation_date'])->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <div id="preview-status" class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    {{ ucfirst($invoice['payment_status']) }}
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div id="preview-patient" class="text-sm">
                            <strong>Patient:</strong> 
                            <span class="text-gray-600">Select a patient to see details</span>
                        </div>
                        
                        <hr>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span id="preview-subtotal">${{ number_format($invoice['total_amount'] - ($invoice['tax'] ?? 0), 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tax:</span>
                                <span id="preview-tax">${{ number_format($invoice['tax'] ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-lg border-t pt-2">
                                <span>Total:</span>
                                <span id="preview-total">${{ number_format($invoice['total_amount'], 2) }}</span>
                            </div>
                        </div>
                        
                        <div id="preview-notes" class="text-sm text-gray-600 mt-3">
                            @if($invoice['notes'])
                                <strong>Notes:</strong> {{ $invoice['notes'] }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="lg:col-span-2 flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.invoices.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Update Invoice
                    </button>
                </div>
            </form>
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
            
            let patientInfo = `<strong>Patient:</strong> ${name}`;
            if (phone) patientInfo += `<br><small>Phone: ${phone}</small>`;
            if (email) patientInfo += `<br><small>Email: ${email}</small>`;
            
            previewPatient.innerHTML = patientInfo;
        } else {
            previewPatient.innerHTML = '<strong>Patient:</strong> <span class="text-gray-600">Select a patient to see details</span>';
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
        
        statusElement.className = `px-2 py-1 text-xs font-medium rounded-full ${statusColors[status] || 'bg-gray-100 text-gray-800'}`;
        statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        
        // Update notes
        const previewNotes = document.getElementById('preview-notes');
        if (notesInput.value.trim()) {
            previewNotes.innerHTML = `<strong>Notes:</strong> ${notesInput.value}`;
            previewNotes.style.display = 'block';
        } else {
            previewNotes.style.display = 'none';
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
