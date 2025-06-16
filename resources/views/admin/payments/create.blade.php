@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
            <h1 class="text-3xl font-bold text-gray-800">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    💳 Add New Payment
                </span>
            </h1>
            <a href="{{ route('admin.payments.index') }}" 
               class="px-4 py-2 text-gray-600 hover:text-white hover:bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl transition-all duration-300 border-2 border-gray-200 hover:border-transparent">
                ← Back to Payments
            </a>
        </div>

        @if($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 shadow-sm">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <h4 class="font-semibold">Please fix the following errors:</h4>
                </div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Invoice Information --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></div>
                    Invoice Information
                </h3>
                <div class="space-y-2">
                    <label for="invoice_id" class="block text-sm font-semibold text-gray-700 mb-2">Invoice *</label>
                    <select name="invoice_id" id="invoice_id"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white @error('invoice_id') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                        <option value="">Select Invoice</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice['invoice_id'] }}" {{ old('invoice_id') == $invoice['invoice_id'] ? 'selected' : '' }}>
                                Invoice #{{ $invoice['invoice_id'] }} - ${{ number_format($invoice['total_amount'], 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <p class="text-red-600 text-sm flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-teal-500 rounded-full mr-3"></div>
                    Payment Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="date_of_payment" class="block text-sm font-semibold text-gray-700 mb-2">Payment Date *</label>
                        <input type="date" name="date_of_payment" id="date_of_payment"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white @error('date_of_payment') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                               value="{{ old('date_of_payment', date('Y-m-d')) }}" required>
                        @error('date_of_payment')
                            <p class="text-red-600 text-sm flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="method_of_payment" class="block text-sm font-semibold text-gray-700 mb-2">Payment Method *</label>
                        <select name="method_of_payment" id="method_of_payment"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white @error('method_of_payment') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror" required>
                            <option value="">Select Payment Method</option>
                            <option value="Cash" {{ old('method_of_payment') == 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="Credit Card" {{ old('method_of_payment') == 'Credit Card' ? 'selected' : '' }}>💳 Credit Card</option>
                        </select>
                        @error('method_of_payment')
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

            {{-- Amount & Reference Information --}}
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-3"></div>
                    Amount & Reference Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="amount_paid" class="block text-sm font-semibold text-gray-700 mb-2">Amount Paid *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-lg">$</span>
                            </div>
                            <input type="number" name="amount_paid" id="amount_paid" step="0.01" min="0"
                                   class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('amount_paid') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                                   placeholder="0.00" value="{{ old('amount_paid') }}" required>
                        </div>
                        @error('amount_paid')
                            <p class="text-red-600 text-sm flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="payment_reference" class="block text-sm font-semibold text-gray-700 mb-2">
                            Payment Reference <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <input type="text" name="payment_reference" id="payment_reference"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('payment_reference') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                               placeholder="Transaction ID, Check #, etc." value="{{ old('payment_reference') }}">
                        @error('payment_reference')
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

            {{-- Staff Information --}}
            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-2xl border border-orange-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-red-500 rounded-full mr-3"></div>
                    Staff Information
                </h3>
                <div class="space-y-2">
                    <label for="received_by" class="block text-sm font-semibold text-gray-700 mb-2">Received By *</label>
                    <input type="text" name="received_by" id="received_by"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 @error('received_by') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror"
                           placeholder="Staff member who received payment" value="{{ old('received_by') }}" required>
                    @error('received_by')
                        <p class="text-red-600 text-sm flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-between pt-6">
                <a href="{{ route('admin.payments.index') }}" 
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
                        Create Payment
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
