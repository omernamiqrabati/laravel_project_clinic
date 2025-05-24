<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            $patients = $this->supabase->fetchTable('patients');
            return view('admin.patients.index', ['patients' => $patients]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching patients: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'address' => 'nullable|string'
            ]);

            $this->supabase->insert_table('patients', $validatedData);

            return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error inserting patient: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $patient = $this->supabase->fetchById('patients', $id);
            return view('admin.patients.edit', compact('patient'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching patient: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $validatedData = $request->validate([
                'date_of_birth' => 'required|date',
                'address' => 'nullable|string',
                'gender' => 'required|string',
            ]);

            $this->supabase->updateById('patients', $id, $validatedData);

            return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating patient: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {


            $this->supabase->deleteById('patients', $id);

            return redirect()->route('admin.patients.index')->with('success', 'Patient deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting patient: ' . $e->getMessage());
        }
    }
}