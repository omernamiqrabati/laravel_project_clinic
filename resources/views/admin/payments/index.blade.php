@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Payments</h1>
        <a href="{{ route('admin.payments.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Payment</a>
        <table class="table-auto w-full mt-6 border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Invoice ID</th>
                    <th class="px-4 py-2 border">Payment Date</th>
                    <th class="px-4 py-2 border">Amount Paid</th>
                    <th class="px-4 py-2 border">Payment Method</th>
                    <th class="px-4 py-2 border">Payment Status</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr class="odd:bg-white even:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $payment['invoice_id'] }}</td>
                        <td class="px-4 py-2 border">{{ $payment['payment_date'] }}</td>
                        <td class="px-4 py-2 border">${{ number_format($payment['amount_paid'], 2) }}</td>
                        <td class="px-4 py-2 border">{{ $payment['payment_method'] }}</td>
                        <td class="px-4 py-2 border">{{ $payment['payment_status'] }}</td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('admin.payments.edit', $payment['id']) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('admin.payments.destroy', $payment['id']) }}" method="POST" class="inline-block">
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
