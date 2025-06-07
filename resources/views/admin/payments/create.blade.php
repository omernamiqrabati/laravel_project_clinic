@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-2xl font-semibold text-blue-800 mb-6 border-b pb-2">🦷 Add New Payment</h2>

    @if($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
            <strong class="block font-semibold mb-2">Please fix the following errors:</strong>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Invoice Selection --}}
        <div>
            <label for="invoice_id" class="block text-sm font-medium text-gray-700">Invoice</label>
            <select name="invoice_id" id="invoice_id"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Select Invoice</option>
                @foreach($invoices as $invoice)
                    <option value="{{ $invoice['invoice_id'] }}" {{ old('invoice_id') == $invoice['invoice_id'] ? 'selected' : '' }}>
                        Invoice #{{ $invoice['invoice_id'] }} - ${{ number_format($invoice['total_amount'], 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Payment Details --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="date_of_payment" class="block text-sm font-medium text-gray-700">Payment Date</label>
                <input type="date" name="date_of_payment" id="date_of_payment"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('date_of_payment', date('Y-m-d')) }}" required>
            </div>

            <div>
                <label for="method_of_payment" class="block text-sm font-medium text-gray-700">Payment Method</label>
                <select name="method_of_payment" id="method_of_payment"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Payment Method</option>
                    <option value="Cash" {{ old('method_of_payment') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Credit Card" {{ old('method_of_payment') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="Debit Card" {{ old('method_of_payment') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                    <option value="Bank Transfer" {{ old('method_of_payment') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Check" {{ old('method_of_payment') == 'Check' ? 'selected' : '' }}>Check</option>
                    <option value="Insurance" {{ old('method_of_payment') == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                </select>
            </div>
        </div>

        {{-- Amount and Reference --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="amount_paid" class="block text-sm font-medium text-gray-700">Amount Paid</label>
                <div class="mt-1 relative rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" name="amount_paid" id="amount_paid" step="0.01" min="0"
                        class="block w-full pl-7 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="0.00" value="{{ old('amount_paid') }}" required>
                </div>
            </div>

            <div>
                <label for="payment_reference" class="block text-sm font-medium text-gray-700">Payment Reference <span class="text-gray-400">(Optional)</span></label>
                <input type="text" name="payment_reference" id="payment_reference"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Transaction ID, Check #, etc." value="{{ old('payment_reference') }}">
            </div>
        </div>

        {{-- Received By --}}
        <div>
            <label for="received_by" class="block text-sm font-medium text-gray-700">Received By</label>
            <input type="text" name="received_by" id="received_by"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Staff member who received payment" value="{{ old('received_by') }}" required>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end pt-4">
            <button type="submit"
                class="inline-flex items-center px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Create Payment
            </button>
        </div>
    </form>
</div>
@endsection
