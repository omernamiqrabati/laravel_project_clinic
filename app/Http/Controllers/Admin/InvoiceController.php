<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            $invoices = $this->supabase->fetchTable('invoices');
            
            // Fetch patient names for display
            $patients_name = $this->supabase->fetchByQuery('user_profiles', [
                'role' => 'patient'
            ], ['first_name', 'last_name','id']);
            
            return view('admin.invoices.index', [
                'invoices' => $invoices,
                'patients_name' => $patients_name
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching invoices: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.invoices.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'appointment_id' => 'required|integer',
                'total_amount' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0',
                'payment_status' => 'required|string|in:Pending,Paid,Overdue,Cancelled'
            ]);

            $this->supabase->insert_table('invoices', $validatedData);

            return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $invoice = $this->supabase->fetchById('invoices', $id);
            return view('admin.invoices.edit', compact('invoice'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching invoice: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'appointment_id' => 'required|integer',
                'total_amount' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0',
                'payment_status' => 'required|string|in:Pending,Paid,Overdue,Cancelled'
            ]);

            $this->supabase->updateById('invoices', $id, $validatedData);

            return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->supabase->deleteById('invoices', $id);

            return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }
}