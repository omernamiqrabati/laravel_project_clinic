@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-800">Invoices</h1>
                <a href="{{ route('admin.invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Add New Invoice
                </a>
            </div>
        </div>

        <div class="p-6">
            @if(isset($invoices) && count($invoices) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-blue-50 border-b border-blue-200">
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Invoice ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Appointment ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Tax</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Payment Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Creation Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoices as $invoice)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                            {{ substr($invoice['invoice_id'], 0, 8) }}...
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(isset($patients) && isset($invoice['patient_id']))
                                            @php
                                                $patient = collect($patients)->firstWhere('patient_id', $invoice['patient_id']);
                                            @endphp
                                            {{ $patient ? ($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '') : 'N/A' }}
                                        @else
                                            <span class="font-mono text-xs text-gray-500">{{ substr($invoice['patient_id'] ?? 'N/A', 0, 8) }}...</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                            {{ $invoice['appointment_id'] ? substr($invoice['appointment_id'], 0, 8) . '...' : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        ${{ number_format((float)$invoice['total_amount'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format((float)($invoice['tax'] ?? 0), 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'paid' => 'bg-green-100 text-green-800',
                                                'paying' => 'bg-yellow-100 text-yellow-800',
                                                'unpaid' => 'bg-red-100 text-red-800'
                                            ];
                                            $status = strtolower($invoice['payment_status'] ?? 'unpaid');
                                            $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                            $statusLabel = ucfirst($status);
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ isset($invoice['creation_date']) ? \Carbon\Carbon::parse($invoice['creation_date'])->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="max-w-xs truncate" title="{{ $invoice['notes'] ?? '' }}">
                                            {{ $invoice['notes'] ? Str::limit($invoice['notes'], 30) : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            
                                            <!-- Edit Invoice -->
                                            <a href="{{ route('admin.invoices.edit', $invoice['invoice_id']) }}" 
                                               class="text-indigo-600 hover:text-indigo-800 transition-colors" 
                                               title="Edit Invoice">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            
                                            <!-- Mark as Paid (only for unpaid invoices) -->
                                            @if($invoice['payment_status'] !== 'paid')
                                                <form method="POST" action="{{ route('admin.invoices.update', $invoice['invoice_id']) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="payment_status" value="paid">
                                                    <input type="hidden" name="patient_id" value="{{ $invoice['patient_id'] }}">
                                                    <input type="hidden" name="appointment_id" value="{{ $invoice['appointment_id'] }}">
                                                    <input type="hidden" name="total_amount" value="{{ $invoice['total_amount'] }}">
                                                    <input type="hidden" name="tax" value="{{ $invoice['tax'] ?? 0 }}">
                                                    <button type="submit" 
                                                            class="text-green-600 hover:text-green-800 transition-colors" 
                                                            title="Mark as Paid"
                                                            onclick="return confirm('Mark this invoice as paid?')">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <!-- Delete Invoice -->
                                            <form method="POST" action="{{ route('admin.invoices.destroy', $invoice['invoice_id']) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 transition-colors" 
                                                        title="Delete Invoice"
                                                        onclick="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Statistics -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    @php
                        $totalInvoices = count($invoices);
                        $paidInvoices = collect($invoices)->where('payment_status', 'paid')->count();
                        $unpaidInvoices = collect($invoices)->where('payment_status', 'unpaid')->count();
                        $totalAmount = collect($invoices)->sum(function($invoice) {
                            return (float)$invoice['total_amount'];
                        });
                        $paidAmount = collect($invoices)->where('payment_status', 'paid')->sum(function($invoice) {
                            return (float)$invoice['total_amount'];
                        });
                    @endphp
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-blue-700">Total Invoices</h3>
                        <p class="text-2xl font-bold text-blue-900">{{ $totalInvoices }}</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-green-700">Paid Invoices</h3>
                        <p class="text-2xl font-bold text-green-900">{{ $paidInvoices }}</p>
                        <p class="text-sm text-green-600">${{ number_format($paidAmount, 2) }}</p>
                    </div>
                    
                    <div class="bg-red-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-red-700">Unpaid Invoices</h3>
                        <p class="text-2xl font-bold text-red-900">{{ $unpaidInvoices }}</p>
                        <p class="text-sm text-red-600">${{ number_format($totalAmount - $paidAmount, 2) }}</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700">Total Amount</h3>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($totalAmount, 2) }}</p>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-blue-50 rounded-lg p-8 max-w-md mx-auto">
                        <div class="text-blue-600 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-blue-800 mb-2">No invoices found</h3>
                        <p class="text-blue-600 mb-4">Get started by creating your first invoice.</p>
                        <a href="{{ route('admin.invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Create Invoice
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection