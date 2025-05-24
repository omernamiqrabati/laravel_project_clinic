<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $payments = [
        [
            'id' => 1,
            'invoice_id' => 101,
            'payment_date' => '2025-04-01',
            'amount_paid' => 150.00,
            'payment_method' => 'Credit Card',
            'payment_status' => 'Completed',
        ],
        [
            'id' => 2,
            'invoice_id' => 102,
            'payment_date' => '2025-04-05',
            'amount_paid' => 200.00,
            'payment_method' => 'Cash',
            'payment_status' => 'Pending',
        ],
        // Add more static payments as needed
    ];

    public function index()
    {
        return view('admin.payments.index', ['payments' => $this->payments]);
    }

    public function create()
    {
        return view('admin.payments.create');
    }

    public function store(Request $request)
    {
        // Handle storing new payment (for demonstration, we'll just redirect)
        return redirect()->route('admin.payments.index');
    }

    public function edit($id)
    {
        $payment = collect($this->payments)->firstWhere('id', $id);
        return view('admin.payments.edit', ['payment' => $payment]);
    }

    public function update(Request $request, $id)
    {
        // Handle updating payment (for demonstration, we'll just redirect)
        return redirect()->route('admin.payments.index');
    }

    public function destroy($id)
    {
        // Handle deleting payment (for demonstration, we'll just redirect)
        return redirect()->route('admin.payments.index');
    }
}
