@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Add New Payment</h1>
        <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="invoice_id" class="block text-sm font-semibold">Invoice ID</label>
                <input type="number" name="invoice_id" id="invoice_id" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="payment_date" class="block text-sm font-semibold">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="amount_paid" class="block text-sm font-semibold">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="payment_method" class="block text-sm font-semibold">Payment Method</label>
                <select name="payment_method" id="payment_method" class="w-full border border-gray-300 px-4 py-2 rounded" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div>
                <label for="payment_status" class="block text-sm font-semibold">Payment Status</label>
                <select name="payment_status" id="payment_status" class="w-full border border-gray-300 px-4 py-2 rounded" required>
                    <option value="Completed">Completed</option>
                    <option value="Pending">Pending</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save Payment</button>
        </form>
    </div>
@endsection
