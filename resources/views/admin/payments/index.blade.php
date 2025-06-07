@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-800">🦷 Dental Clinic – Payment Management</h1>
                <a href="{{ route('admin.payments.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    + Add New Payment
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 px-5 py-3 rounded-md bg-green-100 border border-green-400 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 px-5 py-3 rounded-md bg-red-100 border border-red-400 text-red-800">
                {{ session('error') }}
            </div>
        @endif        <div class="p-6">
            @if(isset($payments) && count($payments) > 0)
                {{-- Mobile Card View --}}
                <div class="block md:hidden space-y-4">
                    @foreach($payments as $payment)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">#{{ $payment['payment_id'] }}</h3>
                                    <p class="text-sm text-gray-600">Invoice #{{ $payment['invoice_id'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">${{ number_format($payment['amount_paid'], 2) }}</p>
                                    <p class="text-sm text-gray-600">{{ date('M d, Y', strtotime($payment['date_of_payment'])) }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                @php
                                    $methodColors = [
                                        'Cash' => 'bg-green-100 text-green-800',
                                        'Credit Card' => 'bg-blue-100 text-blue-800',
                                        'Debit Card' => 'bg-purple-100 text-purple-800',
                                        'Bank Transfer' => 'bg-yellow-100 text-yellow-800',
                                        'Check' => 'bg-orange-100 text-orange-800',
                                        'Insurance' => 'bg-indigo-100 text-indigo-800'
                                    ];
                                    $methodColor = $methodColors[$payment['method_of_payment']] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $methodColor }}">
                                    {{ $payment['method_of_payment'] }}
                                </span>
                                @if($payment['payment_reference'])
                                    <span class="ml-2 text-sm text-gray-600">Ref: {{ $payment['payment_reference'] }}</span>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">By: {{ $payment['received_by'] }}</span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.payments.edit', $payment['payment_id']) }}"
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.payments.destroy', $payment['payment_id']) }}"
                                          method="POST" onsubmit="return confirm('Are you sure?');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-blue-50 border-b border-blue-200">
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Payment</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Received By</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-4 py-4 text-sm">
                                        <div>
                                            <p class="font-medium text-gray-900">#{{ $payment['payment_id'] }}</p>
                                            <p class="text-gray-600">Invoice #{{ $payment['invoice_id'] }}</p>
                                            @if($payment['payment_reference'])
                                                <p class="text-xs text-gray-500">Ref: {{ $payment['payment_reference'] }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ date('M d, Y', strtotime($payment['date_of_payment'])) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @php
                                            $methodColors = [
                                                'Cash' => 'bg-green-100 text-green-800',
                                                'Credit Card' => 'bg-blue-100 text-blue-800',
                                                'Debit Card' => 'bg-purple-100 text-purple-800',
                                                'Bank Transfer' => 'bg-yellow-100 text-yellow-800',
                                                'Check' => 'bg-orange-100 text-orange-800',
                                                'Insurance' => 'bg-indigo-100 text-indigo-800'
                                            ];
                                            $methodColor = $methodColors[$payment['method_of_payment']] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $methodColor }}">
                                            {{ $payment['method_of_payment'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        ${{ number_format($payment['amount_paid'], 2) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment['received_by'] }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.payments.edit', $payment['payment_id']) }}"
                                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.payments.destroy', $payment['payment_id']) }}"
                                                  method="POST" onsubmit="return confirm('Are you sure you want to delete this payment?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-800 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-blue-50 rounded-lg p-8 max-w-md mx-auto">
                        <div class="text-blue-600 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-blue-800 mb-2">No payments found</h3>
                        <p class="text-blue-600 mb-4">Get started by recording your first payment.</p>
                        <a href="{{ route('admin.payments.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Create Payment
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection