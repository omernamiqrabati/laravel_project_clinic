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
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cost' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1|max:1440', // Max 24 hours
            'is_active' => 'boolean'
        ]);

        // Set default values
        $validatedData['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;
        // Don't manually set created_at as the database handles it with DEFAULT now()

        try {
            // Insert the treatment into Supabase
            $result = $this->supabase->insert('treatments', $validatedData);
            
            if ($result) {
                return redirect()->route('admin.treatments.index')
                    ->with('success', 'Treatment created successfully!');
            } else {
                return back()->withInput()
                    ->with('error', 'Failed to create treatment. Please try again.');
            }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating treatment: ' . $e->getMessage());
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
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cost' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1|max:1440', // Max 24 hours
            'is_active' => 'boolean'
        ]);

        // Set default values
        $validatedData['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;
        // Don't manually set updated_at as SupabaseService handles it automatically

        try {
            // Update the treatment in Supabase using updateById
            $result = $this->supabase->updateById('treatments', $id, $validatedData);
            
            if ($result) {
                return redirect()->route('admin.treatments.index')
                    ->with('success', 'Treatment updated successfully!');
            } else {
                return back()->withInput()
                    ->with('error', 'Failed to update treatment. Please try again.');
            }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating treatment: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Check if treatment is being used in any appointments using fetchByQuery
            $appointments = $this->supabase->fetchByQuery('appointments', [
                'treatment_id' => $id
            ]);

            if (!empty($appointments)) {
                return back()->with('error', 'Cannot delete treatment as it is being used in appointments.');
            }

            // Delete the treatment from Supabase using deleteById
            $result = $this->supabase->deleteById('treatments', $id);
            
            if ($result) {
                return redirect()->route('admin.treatments.index')
                    ->with('success', 'Treatment deleted successfully!');
            } else {
                return back()->with('error', 'Failed to delete treatment. Please try again.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting treatment: ' . $e->getMessage());
        }
    }
}