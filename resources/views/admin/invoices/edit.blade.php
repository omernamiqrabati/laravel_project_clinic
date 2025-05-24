@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Edit Invoice</h1>
        <form action="{{ route('admin.invoices.update', $invoice['id']) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="patient_id" class="block text-sm font-semibold">Patient ID</label>
                <input type="number" name="patient_id" id="patient_id" value="{{ $invoice['patient_id'] }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <div>
                <label for="appointment_id" class="block text-sm font-semibold">Appointment ID</label>
                <input type="number" name="appointment_id" id="appointment_id" value="{{ $invoice['appointment_id'] }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <div>
                <label for="total_amount" class="block text-sm font-semibold">Total Amount</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount" value="{{ $invoice['total_amount'] }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <div>
                <label for="payment_status" class="block text-sm font-semibold">Payment Status</label>
                <select name="payment_status" id="payment_status" class="w-full border border-gray-300 px-4 py-2 rounded" required>
                    <option value="Paid" {{ $invoice['payment_status'] === 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Unpaid" {{ $invoice['payment_status'] === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>

            <div>
                <label for="issue_date" class="block text-sm font-semibold">Issue Date</label>
                <input type="date" name="issue_date" id="issue_date" value="{{ $invoice['issue_date'] }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <div>
                <label for="due_date" class="block text-sm font-semibold">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="{{ $invoice['due_date'] }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Invoice</button>
        </form>
    </div>
@endsection
