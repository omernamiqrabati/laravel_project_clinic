@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Add New Invoice</h1>
        <form action="{{ route('admin.invoices.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="patient_id" class="block text-sm font-semibold">Patient ID</label>
                <input type="number" name="patient_id" id="patient_id" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="appointment_id" class="block text-sm font-semibold">Appointment ID</label>
                <input type="number" name="appointment_id" id="appointment_id" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="total_amount" class="block text-sm font-semibold">Total Amount</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="payment_status" class="block text-sm font-semibold">Payment Status</label>
                <select name="payment_status" id="payment_status" class="w-full border border-gray-300 px-4 py-2 rounded" required>
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                </select>
            </div>
            <div>
                <label for="issue_date" class="block text-sm font-semibold">Issue Date</label>
                <input type="date" name="issue_date" id="issue_date" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <div>
                <label for="due_date" class="block text-sm font-semibold">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save Invoice</button>
        </form>
    </div>
@endsection
