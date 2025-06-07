<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            $treatments = $this->supabase->fetchTable('treatments');
            return view('admin.treatments.index', ['treatments' => $treatments]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching treatments: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.treatments.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'cost' => 'required|numeric|min:0',
                'duration_minutes' => 'required|integer|min:1',
                'is_active' => 'boolean'
            ]);

            // Default is_active to true if not provided
            $validatedData['is_active'] = $validatedData['is_active'] ?? true;

            $this->supabase->insert_table('treatments', $validatedData);

            return redirect()->route('admin.treatments.index')->with('success', 'Treatment created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating treatment: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $treatment = $this->supabase->fetchById('treatments', $id);
            return view('admin.treatments.edit', compact('treatment'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching treatment: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'cost' => 'required|numeric|min:0',
                'duration_minutes' => 'required|integer|min:1',
                'is_active' => 'boolean'
            ]);

            // Default is_active to true if not provided
            $validatedData['is_active'] = $validatedData['is_active'] ?? true;

            $this->supabase->updateById('treatments', $id, $validatedData);

            return redirect()->route('admin.treatments.index')->with('success', 'Treatment updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating treatment: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->supabase->deleteById('treatments', $id);

            return redirect()->route('admin.treatments.index')->with('success', 'Treatment deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting treatment: ' . $e->getMessage());
        }
    }
}