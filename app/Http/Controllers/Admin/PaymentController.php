<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            $payments = $this->supabase->fetchTable('payments');
            
            // Fetch invoice data for display
            $invoices = $this->supabase->fetchTable('invoices');
            
            return view('admin.payments.index', [
                'payments' => $payments,
                'invoices' => $invoices
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching payments: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $invoices = $this->supabase->fetchTable('invoices');
            return view('admin.payments.create', ['invoices' => $invoices]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching invoices: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'invoice_id' => 'required|integer',
                'date_of_payment' => 'required|date',
                'method_of_payment' => 'required|string|in:Cash,Credit Card,Debit Card,Bank Transfer,Check,Insurance',
                'amount_paid' => 'required|numeric|min:0',
                'payment_reference' => 'nullable|string|max:255',
                'received_by' => 'required|string|max:255'
            ]);

            $this->supabase->insert_table('payments', $validatedData);

            return redirect()->route('admin.payments.index')->with('success', 'Payment created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating payment: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $payment = $this->supabase->fetchById('payments', $id);
            $invoices = $this->supabase->fetchTable('invoices');
            return view('admin.payments.edit', compact('payment', 'invoices'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching payment: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'invoice_id' => 'required|integer',
                'date_of_payment' => 'required|date',
                'method_of_payment' => 'required|string|in:Cash,Credit Card,Debit Card,Bank Transfer,Check,Insurance',
                'amount_paid' => 'required|numeric|min:0',
                'payment_reference' => 'nullable|string|max:255',
                'received_by' => 'required|string|max:255'
            ]);

            $this->supabase->updateById('payments', $id, $validatedData);

            return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating payment: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->supabase->deleteById('payments', $id);

            return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting payment: ' . $e->getMessage());
        }
    }
}