<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            $dentists = $this->supabase->fetchTable('dentists');

            // Ensure all fields are strings
            $dentists = array_map(function ($dentist) {
                $dentist['bio'] = is_array($dentist['bio']) ? json_encode($dentist['bio']) : $dentist['bio'];
                $dentist['working_hours'] = is_array($dentist['working_hours']) ? json_encode($dentist['working_hours']) : $dentist['working_hours'];
                $dentist['off_days'] = is_array($dentist['off_days']) ? json_encode($dentist['off_days']) : $dentist['off_days'];
                return $dentist;
            }, $dentists);

            return view('admin.dentists.index', ['dentists' => $dentists]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching dentists: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.dentists.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'specialization' => 'required|string',
                'bio' => 'nullable|string',
                'working_hours' => 'required|json',
                'off_days' => 'nullable|string'
            ]);

            // Decode JSON working_hours into an array
            $validatedData['working_hours'] = json_decode($validatedData['working_hours'], true);

            $this->supabase->insert_table('dentists', $validatedData);

            return redirect()->route('admin.dentists.index')->with('success', 'Dentist created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error inserting dentist: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $dentist = $this->supabase->fetchById('dentists', $id);

            // Decode working_hours JSON string to array
            $dentist['working_hours'] = is_string($dentist['working_hours']) ? json_decode($dentist['working_hours'], true) : $dentist['working_hours'];

            return view('admin.dentists.edit', compact('dentist'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching dentist: ' . $e->getMessage());
        }
    }



public function update(Request $request, $dentist_id)
{
    try {
        
        
        return $request;

    } catch (\Exception $e) {
        return 5;
    }
}




}