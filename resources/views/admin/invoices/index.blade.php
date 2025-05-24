@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Invoices</h1>
        <a href="{{ route('admin.invoices.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Invoice</a>
        <table class="table-auto w-full mt-6 border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Patient ID</th>
                    <th class="px-4 py-2 border">Appointment ID</th>
                    <th class="px-4 py-2 border">Total Amount</th>
                    <th class="px-4 py-2 border">Payment Status</th>
                    <th class="px-4 py-2 border">Issue Date</th>
                    <th class="px-4 py-2 border">Due Date</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr class="odd:bg-white even:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $invoice['patient_id'] }}</td>
                        <td class="px-4 py-2 border">{{ $invoice['appointment_id'] }}</td>
                        <td class="px-4 py-2 border">${{ number_format($invoice['total_amount'], 2) }}</td>
                        <td class="px-4 py-2 border">{{ $invoice['payment_status'] }}</td>
                        <td class="px-4 py-2 border">{{ $invoice['issue_date'] }}</td>
                        <td class="px-4 py-2 border">{{ $invoice['due_date'] }}</td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('admin.invoices.edit', $invoice['id']) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('admin.invoices.destroy', $invoice['id']) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
