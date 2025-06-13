<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use App\Rules\UniqueSupabaseEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReceptionistController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            // Get all receptionists using the database function
            $result = $this->supabase->listAllReceptionists();
            
            if (!$result['success']) {
                return back()->with('error', 'Error fetching receptionists: ' . $result['error']);
            }
            
            $receptionists = $result['data'];
            
            Log::info('Receptionists data for index page', [
                'total_count' => count($receptionists),
                'sample_data' => array_slice($receptionists, 0, 2)
            ]);

            return view('admin.receptionists.index', ['receptionists' => $receptionists]);
        } catch (\Exception $e) {
            Log::error('Error in ReceptionistController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error fetching receptionists: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.receptionists.create');
    }

    public function store(Request $request)
    {
        Log::info('ReceptionistController@store started', ['request_data' => $request->all()]);
        
        try {
            // 1. Validate the request data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', new UniqueSupabaseEmail()],
                'phone' => 'required|string|max:20',
                'avatar' => 'nullable|image|max:5120' // 5MB max
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'phone.required' => 'Phone number is required.',
                'avatar.image' => 'Avatar must be an image file.',
                'avatar.max' => 'Avatar file size must not exceed 5MB.'
            ]);

            Log::info('Validation passed', ['validated_data' => $validatedData]);

            // 2. Prepare data for receptionist creation
            $receptionistData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone']
            ];

            // 3. Create receptionist using SupabaseService
            $result = $this->supabase->createCompleteReceptionist($receptionistData);

            if (!$result['success']) {
                return back()->withInput()->with('error', $result['error']);
            }

            // Debug the response structure
            Log::info('Receptionist creation result', [
                'full_result' => $result,
                'data_keys' => array_keys($result['data'] ?? []),
                'id_value' => $result['data']['id'] ?? 'NOT_SET',
                'id_type' => gettype($result['data']['id'] ?? null)
            ]);

            $receptionistId = $result['data']['id'];
            
            // Validate that we have a proper UUID
            if (empty($receptionistId) || !is_string($receptionistId) || strlen($receptionistId) < 30) {
                Log::error('Invalid receptionist ID returned from database function', [
                    'id' => $receptionistId,
                    'full_result' => $result
                ]);
                return back()->withInput()->with('error', 'Receptionist creation failed: Invalid ID returned from database');
            }

            // 4. Handle avatar upload if present - exactly like patients
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                
                Log::info('Avatar file details', [
                    'receptionist_id' => $receptionistId,
                    'original_name' => $avatarFile->getClientOriginalName(),
                    'mime_type' => $avatarFile->getMimeType(),
                    'size' => $avatarFile->getSize(),
                    'extension' => $avatarFile->getClientOriginalExtension(),
                    'is_valid' => $avatarFile->isValid(),
                    'temp_path' => $avatarFile->getRealPath()
                ]);
                
                if (!$avatarFile->isValid()) {
                    Log::error('Avatar file is not valid', [
                        'receptionist_id' => $receptionistId,
                        'error' => $avatarFile->getError(),
                        'error_message' => $avatarFile->getErrorMessage()
                    ]);
                    return redirect()->route('admin.receptionists.index')
                        ->with('warning', 'Receptionist created but avatar could not be processed: File is not valid');
                }
                
                // Upload avatar to Supabase storage
                $uploadResult = $this->supabase->uploadAvatarAsAdmin($receptionistId, $avatarFile);
                
                // Check upload result
                if ($uploadResult['success']) {
                    Log::info('Avatar uploaded successfully', [
                        'receptionist_id' => $receptionistId,
                        'avatar_url' => $uploadResult['public_url']
                    ]);
                    
                    // Update the user profile with the avatar URL
                    $avatarUpdateResult = $this->supabase->updateUserAvatarPath($receptionistId, $uploadResult['public_url']);
                    if (!$avatarUpdateResult['success']) {
                        Log::warning('Avatar uploaded but failed to update user profile', [
                            'receptionist_id' => $receptionistId,
                            'avatar_url' => $uploadResult['public_url'],
                            'error' => $avatarUpdateResult['error']
                        ]);
                        return redirect()->route('admin.receptionists.index')
                            ->with('warning', 'Receptionist created and avatar uploaded, but failed to update profile: ' . $avatarUpdateResult['error']);
                    }
                } else {
                    Log::error('Failed to upload avatar', [
                        'receptionist_id' => $receptionistId,
                        'error' => $uploadResult['error']
                    ]);
                    
                    // Return with partial success message
                    return redirect()->route('admin.receptionists.index')
                        ->with('warning', 'Receptionist created but avatar upload failed: ' . $uploadResult['error']);
                }
            } else {
                Log::info('No avatar file provided in the request', [
                    'receptionist_id' => $receptionistId
                ]);
            }

            return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating receptionist in ReceptionistController@store', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error creating receptionist: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // Get complete receptionist data including user profile information
            $result = $this->supabase->getCompleteReceptionist($id);
            
            if (!$result['success']) {
                return back()->with('error', 'Error fetching receptionist: ' . $result['error']);
            }

            $receptionist = $result['data'];

            // Debug: Log the receptionist data to check avatar
            Log::info('Receptionist edit data', [
                'receptionist_id' => $id,
                'avatar_in_response' => $receptionist['avatar'] ?? 'NOT_SET',
                'avatar_type' => gettype($receptionist['avatar'] ?? null),
                'all_keys' => array_keys($receptionist)
            ]);

            return view('admin.receptionists.edit', compact('receptionist'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching receptionist: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('ReceptionistController@update started', ['receptionist_id' => $id, 'request_data' => $request->all()]);
        
        try {
            // Get current receptionist to find the user_id for email validation
            $currentReceptionist = $this->supabase->getCompleteReceptionist($id);
            $currentUserId = null;
            if ($currentReceptionist['success'] && isset($currentReceptionist['data']['user_id'])) {
                $currentUserId = $currentReceptionist['data']['user_id'];
            }

            // 1. Validate the request data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', new UniqueSupabaseEmail($currentUserId)],
                'phone' => 'required|string|max:20',
                'avatar' => 'nullable|image|max:5120' // 5MB max
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'phone.required' => 'Phone number is required.',
                'avatar.image' => 'Avatar must be an image file.',
                'avatar.max' => 'Avatar file size must not exceed 5MB.'
            ]);

            // 2. Update receptionist data using database function
            $receptionistData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone']
            ];

            Log::info('Updating receptionist data', [
                'receptionist_id' => $id,
                'data' => $receptionistData
            ]);

            $result = $this->supabase->updateCompleteReceptionist($id, $receptionistData);

            if (!$result['success']) {
                return back()->withInput()->with('error', $result['error']);
            }

            // 3. Handle avatar upload if present
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                if ($avatarFile->isValid()) {
                    $uploadResult = $this->supabase->uploadAvatarAsAdmin($id, $avatarFile);
                    if (!$uploadResult['success']) {
                        return redirect()->route('admin.receptionists.index')
                            ->with('warning', 'Receptionist updated but avatar upload failed: ' . $uploadResult['error']);
                    }
                    
                    // Update the user profile with the avatar URL
                    if (isset($uploadResult['public_url'])) {
                        $avatarUpdateResult = $this->supabase->updateUserAvatarPath($id, $uploadResult['public_url']);
                        if (!$avatarUpdateResult['success']) {
                            Log::warning('Avatar uploaded but failed to update user profile', [
                                'receptionist_id' => $id,
                                'avatar_url' => $uploadResult['public_url'],
                                'error' => $avatarUpdateResult['error']
                            ]);
                        }
                    }
                } else {
                    return redirect()->route('admin.receptionists.index')
                        ->with('warning', 'Receptionist updated but avatar could not be processed: File is not valid');
                }
            }

            Log::info('Receptionist updated successfully', ['receptionist_id' => $id]);
            return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating receptionist in ReceptionistController@update', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error updating receptionist: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('ReceptionistController@destroy started', ['receptionist_id' => $id]);

            // Use the delete function to safely remove all receptionist data
            $result = $this->supabase->deleteCompleteReceptionist($id);

            if (!$result['success']) {
                return back()->with('error', 'Error deleting receptionist: ' . $result['error']);
            }

            Log::info('Receptionist deleted successfully', ['receptionist_id' => $id]);
            return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting receptionist in ReceptionistController@destroy', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error deleting receptionist: ' . $e->getMessage());
        }
    }
} 