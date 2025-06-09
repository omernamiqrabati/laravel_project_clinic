<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
            // Validate the request data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'date_of_birth' => 'required|date',
                'gender' => 'required|string|in:Male,Female,Other',
                'address' => 'required|string|max:500',
                'email_verified' => 'nullable|boolean',
                'phone_verified' => 'nullable|boolean',
                'avatar' => 'nullable|image|max:5120' // Validate image upload
            ]);

            // Prepare data for patient creation
            $patientData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'gender' => $validatedData['gender'],
                'address' => $validatedData['address'],
                'email_verified' => isset($validatedData['email_verified']),
                'phone_verified' => isset($validatedData['phone_verified'])
            ];

            // Create patient using RPC function
            $result = $this->supabase->createCompletePatient($patientData);
            
            if (!$result['success']) {
                return back()->withInput()->with('error', $result['error']);
            }

            // Get the ID of the newly created patient
            $patientId = $result['data']['id'];
            
            Log::info('Patient created successfully, now handling avatar', [
                'patient_id' => $patientId
            ]);

            // Handle file upload if avatar is present
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                
                // Log detailed information about the file
                Log::info('Avatar file details', [
                    'patient_id' => $patientId,
                    'original_name' => $avatarFile->getClientOriginalName(),
                    'mime_type' => $avatarFile->getMimeType(),
                    'size' => $avatarFile->getSize(),
                    'extension' => $avatarFile->getClientOriginalExtension(),
                    'is_valid' => $avatarFile->isValid(),
                    'temp_path' => $avatarFile->getRealPath()
                ]);
                
                if (!$avatarFile->isValid()) {
                    Log::error('Avatar file is not valid', [
                        'patient_id' => $patientId,
                        'error' => $avatarFile->getError(),
                        'error_message' => $avatarFile->getErrorMessage()
                    ]);
                    return redirect()->route('admin.patients.index')
                        ->with('warning', 'Patient created but avatar could not be processed: File is not valid');
                }
                
                // Upload avatar to Supabase storage
                $uploadResult = $this->supabase->uploadUserAvatar($patientId, $avatarFile);
                
                // Check upload result
                if ($uploadResult['success']) {
                    Log::info('Avatar uploaded and profile updated', [
                        'patient_id' => $patientId,
                        'avatar_url' => $uploadResult['public_url']
                    ]);
                } else {
                    Log::error('Failed to upload avatar', [
                        'patient_id' => $patientId,
                        'error' => $uploadResult['error']
                    ]);
                    
                    // Return with partial success message
                    return redirect()->route('admin.patients.index')
                        ->with('warning', 'Patient created but avatar upload failed: ' . $uploadResult['error']);
                }
            } else {
                Log::info('No avatar file provided in the request', [
                    'patient_id' => $patientId
                ]);
            }

            return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully');
        } catch (\Exception $e) {
            Log::error('Error in PatientController@store', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error creating patient: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $patient = $this->supabase->fetchById('patients', $id);
            return view('admin.patients.edit', compact('patient'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching patient details: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                // Basic validation for update
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255',
                'phone' => 'sometimes|required|string|max:20',
                'date_of_birth' => 'sometimes|required|date',
                'gender' => 'sometimes|required|string|in:Male,Female,Other',
                'address' => 'sometimes|required|string|max:500'
            ]);

            // Update both tables as needed
            if (isset($validatedData['first_name']) || isset($validatedData['last_name']) || isset($validatedData['email']) || isset($validatedData['phone'])) {
                $userData = array_intersect_key($validatedData, array_flip(['first_name', 'last_name', 'email', 'phone']));
                $userData['updated_at'] = now();
                $this->supabase->updateById('user_profiles', $id, $userData);
            }

            $patientData = array_intersect_key($validatedData, array_flip(['date_of_birth', 'gender', 'address']));
            if (!empty($patientData)) {
                $patientData['updated_at'] = now();
                $this->supabase->updateById('patients', $id, $patientData);
            }

            return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating patient: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Delete patient record
            $this->supabase->deleteById('patients', $id);
            
            // Also delete the user profile if exists
            try {
                $this->supabase->deleteById('user_profiles', $id);
            } catch (\Exception $e) {
                // Ignore if user_profile doesn't exist
            }
            
            return redirect()->route('admin.patients.index')->with('success', 'Patient deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting patient: ' . $e->getMessage());
        }
    }
}