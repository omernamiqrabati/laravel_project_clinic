<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'specialization' => 'required|string',
                'bio' => 'nullable|string',
                'working_hours_json' => 'required|json',
                'off_days' => 'nullable|string',
                'email_verified' => 'nullable|boolean',
                'phone_verified' => 'nullable|boolean',
                'avatar' => 'nullable|image|max:5120' // Validate image upload
            ]);

            // Prepare data for dentist creation
            $dentistData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'specialization' => $validatedData['specialization'],
                'bio' => $validatedData['bio'] ?? null,
                'working_hours' => json_decode($validatedData['working_hours_json'], true),
                'off_days' => $validatedData['off_days'] ? explode(',', $validatedData['off_days']) : null,
                'email_verified' => isset($validatedData['email_verified']),
                'phone_verified' => isset($validatedData['phone_verified'])
            ];

            // Create dentist using RPC function
            $result = $this->supabase->createCompleteDentist($dentistData);
            
            if (!$result['success']) {
                return back()->withInput()->with('error', $result['error']);
            }

            // Get the ID of the newly created dentist
            $dentistId = $result['data']['id'];
            
            Log::info('Dentist created successfully, now handling avatar', [
                'dentist_id' => $dentistId
            ]);

            // Handle file upload if avatar is present
            if ($request->hasFile('avatar')) {
                Log::info('Avatar file is present in the request', [
                    'dentist_id' => $dentistId,
                    'file_name' => $request->file('avatar')->getClientOriginalName(),
                    'file_size' => $request->file('avatar')->getSize()
                ]);
                
                // Upload avatar to Supabase storage
                $uploadResult = $this->supabase->uploadUserAvatar($dentistId, $request->file('avatar'));
                
                // The uploadUserAvatar method now updates the profile directly
                if (!$uploadResult['success']) {
                    Log::error('Failed to upload avatar', [
                        'dentist_id' => $dentistId,
                        'error' => $uploadResult['error']
                    ]);
                }
            }

            return redirect()->route('admin.dentists.index')->with('success', 'Dentist created successfully');
        } catch (\Exception $e) {
            Log::error('Error in DentistController@store', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error creating dentist: ' . $e->getMessage());
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