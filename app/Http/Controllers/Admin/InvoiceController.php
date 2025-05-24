<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    // Static list of invoices
    private $invoices = [
        [
            'id' => 1,
            'patient_id' => 1,
            'appointment_id' => 1,
            'total_amount' => 150.00,
            'payment_status' => 'Paid',
            'issue_date' => '2025-04-01',
            'due_date' => '2025-04-15',
        ],
        [
            'id' => 2,
            'patient_id' => 2,
            'appointment_id' => 2,
            'total_amount' => 200.00,
            'payment_status' => 'Unpaid',
            'issue_date' => '2025-04-02',
            'due_date' => '2025-04-16',
        ],
    ];

    // Display a listing of invoices
    public function index()
    {
        return view('admin.invoices.index', ['invoices' => $this->invoices]);
    }

    // Show the form for creating a new invoice
    public function create()
    {
        return view('admin.invoices.create');
    }

    // Store a newly created invoice
    public function store()
    {
        $newInvoice = [
            'id' => count($this->invoices) + 1,
            'patient_id' => request('patient_id'),
            'appointment_id' => request('appointment_id'),
            'total_amount' => request('total_amount'),
            'payment_status' => request('payment_status'),
            'issue_date' => request('issue_date'),
            'due_date' => request('due_date'),
        ];

        $this->invoices[] = $newInvoice;

        return redirect()->route('admin.invoices.index');
    }

    // Show the form for editing the specified invoice
    public function edit($id)
    {
        $invoice = collect($this->invoices)->firstWhere('id', $id);
        return view('admin.invoices.edit', compact('invoice'));
    }

    // Update the specified invoice
    public function update($id)
    {
        $index = array_search($id, array_column($this->invoices, 'id'));
        if ($index === false) return redirect()->route('admin.invoices.index');

        $this->invoices[$index] = [
            'id' => $id,
            'patient_id' => request('patient_id'),
            'appointment_id' => request('appointment_id'),
            'total_amount' => request('total_amount'),
            'payment_status' => request('payment_status'),
            'issue_date' => request('issue_date'),
            'due_date' => request('due_date'),
        ];

        return redirect()->route('admin.invoices.index');
    }

    // Remove the specified invoice
    public function destroy($id)
    {
        $this->invoices = array_filter($this->invoices, fn($invoice) => $invoice['id'] != $id);
        return redirect()->route('admin.invoices.index');
    }
}
