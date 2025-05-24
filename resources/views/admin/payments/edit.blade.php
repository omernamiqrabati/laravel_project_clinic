@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Edit Payment</h1>
        <form action="{{ route('admin.payments.update', $payment['id']) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="invoice_id" class="block text-sm font-semibold">Invoice ID</label>
                <input type="number" name="invoice_id" id="invoice_id" value="{{ $payment['invoice_id'] }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="payment_date" class="block text-sm font-semibold">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" value="{{ $payment['payment_date'] }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="amount_paid" class="block text-sm font-semibold">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" id="amount_paid" value="{{ $payment['amount_paid'] }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="payment_method" class="block text-sm font-semibold">Payment Method</label>
                <select name="payment_method" id="payment_method" class="w-full border border-gray-300 px-4 py-2 rounded" required>
                    <option value="Credit Card" {{ $payment['payment_method'] === 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="Cash" {{ $payment['payment_method'] === 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank Transfer" {{ $payment['payment_method'] === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Other" {{ $payment['payment_method'] === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label for="payment_status" class="block text-sm font-semibold">Payment Status</label>
                <select name="payment_status" id="payment_status" class="w-full border border-gray-300 px-4 py-2 rounded" required>
                    <option value="Completed" {{ $payment['payment_status'] === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Pending" {{ $payment['payment_status'] === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Failed" {{ $payment['payment_status'] === 'Failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Payment</button>
        </form>
    </div>
@endsection
