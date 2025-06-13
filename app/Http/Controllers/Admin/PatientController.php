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
            // Use the list_all_patients function to get complete data
            $result = $this->supabase->listAllPatients();
            
            if (!$result['success']) {
                Log::error('Failed to fetch patients from SupabaseService', [
                    'error' => $result['error']
                ]);
                return back()->with('error', 'Failed to fetch patients: ' . $result['error']);
            }

            $patients = $result['data'];
            Log::info('Patients data fetched for index', ['count' => count($patients)]);
            
            return view('admin.patients.index', ['patients' => $patients]);
        } catch (\Exception $e) {
            Log::error('Exception in PatientController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error fetching patients: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        Log::info('PatientController@store started', ['request_data' => $request->except(['avatar'])]);
        
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
                'avatar' => 'nullable|image|max:5120' // Validate image upload
            ]);

            Log::info('Patient validation passed', ['validated_data' => collect($validatedData)->except(['avatar'])->toArray()]);

            // Prepare data for patient creation
            $patientData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'gender' => $validatedData['gender'],
                'address' => $validatedData['address']
            ];

            // Create patient using RPC function
            $result = $this->supabase->createCompletePatient($patientData);
            
            if (!$result['success']) {
                Log::error('Failed to create patient via SupabaseService', [
                    'error' => $result['error'],
                    'patient_data' => $patientData
                ]);
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
                                    $uploadResult = $this->supabase->uploadAvatarAsAdmin($patientId, $avatarFile);
                
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
        Log::info('PatientController@edit started', ['patient_id' => $id]);
        
        try {
            // Use the get_complete_patient function to get full patient data
            $result = $this->supabase->getCompletePatient($id);
            
            if (!$result['success']) {
                Log::error('Failed to fetch patient for edit', [
                    'patient_id' => $id,
                    'error' => $result['error']
                ]);
                return back()->with('error', 'Failed to fetch patient details: ' . $result['error']);
            }

            $patient = $result['data'];
            Log::info('Patient data fetched for edit', ['patient_id' => $id, 'patient_data' => $patient]);
            
            return view('admin.patients.edit', compact('patient'));
        } catch (\Exception $e) {
            Log::error('Exception in PatientController@edit', [
                'patient_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error fetching patient details: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('PatientController@update started', [
            'patient_id' => $id,
            'request_data' => $request->except(['avatar'])
        ]);
        
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
                'avatar' => 'nullable|image|max:5120'
            ]);

            Log::info('Patient update validation passed', [
                'patient_id' => $id,
                'validated_data' => collect($validatedData)->except(['avatar'])->toArray()
            ]);

            // Use the database function to update complete patient data (bypasses RLS issues)
            try {
                $updateData = [
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'phone' => $validatedData['phone'],
                    'date_of_birth' => $validatedData['date_of_birth'],
                    'gender' => $validatedData['gender'],
                    'address' => $validatedData['address']
                ];

                Log::info('Attempting to update complete patient data', [
                    'patient_id' => $id,
                    'data' => $updateData
                ]);

                $updateResult = $this->supabase->updateCompletePatient($id, $updateData);
                
                if (!$updateResult['success']) {
                    Log::error('Failed to update complete patient', [
                        'patient_id' => $id,
                        'error' => $updateResult['error']
                    ]);
                    return back()->withInput()->with('error', 'Failed to update patient: ' . $updateResult['error']);
                }

                $updateData = $updateResult['data'];
                Log::info('Patient updated successfully using database function', [
                    'patient_id' => $id,
                    'result' => $updateData
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to update complete patient data', [
                    'patient_id' => $id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->with('error', 'Failed to update patient: ' . $e->getMessage());
            }

            // Handle avatar upload if present
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                
                Log::info('Processing avatar upload for patient update', [
                    'patient_id' => $id,
                    'file_name' => $avatarFile->getClientOriginalName()
                ]);
                
                if ($avatarFile->isValid()) {
                    $uploadResult = $this->supabase->uploadAvatarAsAdmin($id, $avatarFile);
                    
                    if ($uploadResult['success']) {
                        Log::info('Avatar uploaded successfully, updating patient with new avatar URL', [
                            'patient_id' => $id,
                            'avatar_url' => $uploadResult['public_url']
                        ]);
                        
                        // Update the patient again with the new avatar URL
                        try {
                            $avatarUpdateData = [
                                'first_name' => $validatedData['first_name'],
                                'last_name' => $validatedData['last_name'],
                                'email' => $validatedData['email'],
                                'phone' => $validatedData['phone'],
                                'date_of_birth' => $validatedData['date_of_birth'],
                                'gender' => $validatedData['gender'],
                                'address' => $validatedData['address'],
                                'avatar_url' => $uploadResult['public_url']
                            ];
                            
                            $avatarUpdateResult = $this->supabase->updateCompletePatientWithAvatar($id, $avatarUpdateData);
                            
                            if (!$avatarUpdateResult['success']) {
                                Log::warning('Failed to update patient with avatar URL', [
                                    'patient_id' => $id,
                                    'error' => $avatarUpdateResult['error']
                                ]);
                            }
                            
                        } catch (\Exception $e) {
                            Log::warning('Exception while updating patient with avatar URL', [
                                'patient_id' => $id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    } else {
                        Log::warning('Avatar upload failed during update', [
                            'patient_id' => $id,
                            'error' => $uploadResult['error']
                        ]);
                        return redirect()->route('admin.patients.index')
                            ->with('warning', 'Patient updated but avatar upload failed: ' . $uploadResult['error']);
                    }
                } else {
                    Log::warning('Invalid avatar file during update', [
                        'patient_id' => $id,
                        'error' => $avatarFile->getErrorMessage()
                    ]);
                }
            }

            Log::info('Patient updated successfully', ['patient_id' => $id]);
            return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully');

        } catch (\Exception $e) {
            Log::error('Error in PatientController@update', [
                'patient_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error updating patient: ' . $e->getMessage());
        }   
    }

    public function destroy($id)
    {
        Log::info('PatientController@destroy started', ['patient_id' => $id]);
        
        try {
            // Use the database function to delete complete patient and all related data
            $result = $this->supabase->deleteCompletePatient($id);

            if (!$result['success']) {
                Log::error('Failed to delete patient using database function', [
                    'patient_id' => $id,
                    'error' => $result['error']
                ]);
                return back()->with('error', 'Failed to delete patient: ' . $result['error']);
            }

            $deletionData = $result['data'];
            $patientName = $deletionData['patient_name'] ?? 'Unknown';

            Log::info('Patient deleted successfully using database function', [
                'patient_id' => $id,
                'patient_name' => $patientName,
                'deletion_data' => $deletionData
            ]);

            return redirect()->route('admin.patients.index')
                ->with('success', "Patient {$patientName} has been deleted successfully with all related data");

        } catch (\Exception $e) {
            Log::error('Error deleting patient in PatientController@destroy', [
                'patient_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error deleting patient: ' . $e->getMessage());
        }
    }
}