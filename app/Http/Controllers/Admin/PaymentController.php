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
                'invoice_id' => 'required|uuid',
                'date_of_payment' => 'required|date',
                'method_of_payment' => 'required|string|in:Cash,Credit Card,Debit Card,Bank Transfer,Check,Insurance',
                'amount_paid' => 'required|numeric|min:0',
                'payment_reference' => 'nullable|string|max:255',
                'received_by' => 'required|string|max:255' // Back to string since form sends names
            ]);

            $this->supabase->insert('payments', $validatedData);

            return redirect()->route('admin.payments.index')->with('success', 'Payment created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating payment: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $payment = $this->supabase->fetchById('payments', $id);
            
            if (!$payment) {
                return back()->with('error', 'Payment not found');
            }

            // Format the date for the date input field (YYYY-MM-DD)
            if (isset($payment['date_of_payment'])) {
                $payment['date_of_payment'] = date('Y-m-d', strtotime($payment['date_of_payment']));
            }

            $invoices = $this->supabase->fetchTable('invoices');
            
            return view('admin.payments.edit', [
                'payment' => $payment,
                'invoices' => $invoices
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching payment: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'invoice_id' => 'required|uuid',
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

    // Method to insert static test data
    public function insertStaticData()
    {
        try {
            // Get available invoices
            $invoices = $this->supabase->fetchTable('invoices');
            
            if (empty($invoices)) {
                return back()->with('error', 'No invoices available. Please create invoices first.');
            }

            // Static payment data
            $staticPayments = [
                [
                    'invoice_id' => $invoices[0]['invoice_id'], // Use first available invoice
                    'date_of_payment' => now()->format('Y-m-d H:i:s'),
                    'method_of_payment' => 'cash',
                    'amount_paid' => 150.00,
                    'payment_reference' => 'CASH001',
                    'received_by' => '00000000-0000-0000-0000-000000000001' // Static UUID for received_by
                ],
                [
                    'invoice_id' => count($invoices) > 1 ? $invoices[1]['invoice_id'] : $invoices[0]['invoice_id'],
                    'date_of_payment' => now()->subDays(1)->format('Y-m-d H:i:s'),
                    'method_of_payment' => 'credit_card',
                    'amount_paid' => 200.00,
                    'payment_reference' => 'CC002',
                    'received_by' => '00000000-0000-0000-0000-000000000002'
                ],
                [
                    'invoice_id' => $invoices[0]['invoice_id'],
                    'date_of_payment' => now()->subDays(2)->format('Y-m-d H:i:s'),
                    'method_of_payment' => 'insurance',
                    'amount_paid' => 300.00,
                    'payment_reference' => 'INS003',
                    'received_by' => '00000000-0000-0000-0000-000000000001'
                ]
            ];

            // Insert each payment
            foreach ($staticPayments as $payment) {
                $this->supabase->insert('payments', $payment);
            }

            return redirect()->route('admin.payments.index')->with('success', 'Static payment data inserted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error inserting static data: ' . $e->getMessage());
        }
    }
}