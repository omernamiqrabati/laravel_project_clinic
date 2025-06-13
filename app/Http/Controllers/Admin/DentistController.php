<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // For HTTP client

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
            $result = $this->supabase->listAllDentists();
            
            if (!$result['success']) {
                return back()->with('error', 'Error fetching dentists: ' . $result['error']);
            }

            $dentists = $result['data'];

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
        Log::info('DentistController@store started', ['request_data' => $request->all()]);
        
        try {
            // 1. Validate the request data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'specialization' => 'required|string|max:255',
                'bio' => 'nullable|string|max:1000',
                'avatar' => 'nullable|image|max:5120', // 5MB max
                'working_hours_json' => 'nullable|json',
                'off_days' => 'nullable|json'
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'phone.required' => 'Phone number is required.',
                'specialization.required' => 'Specialization is required.',
                'working_hours_json.json' => 'Working hours data is invalid.',
                'avatar.image' => 'Avatar must be an image file.',
                'avatar.max' => 'Avatar file size must not exceed 5MB.',
                'bio.max' => 'Bio must not exceed 1000 characters.',
            ]);

            // 2. Decode and validate working hours
            $workingHoursJson = $validatedData['working_hours_json'];
            
            // Check if working_hours_json is null but we have working_hours in the request
            if (is_null($workingHoursJson) && $request->has('working_hours')) {
                Log::info('working_hours_json is null, but working_hours field exists', [
                    'working_hours' => $request->input('working_hours')
                ]);
                
                // Convert the working_hours array to the correct format
                $workingHoursArray = $request->input('working_hours');
                $workingHours = [];
                
                foreach ($workingHoursArray as $day => $data) {
                    if (isset($data['start']) && isset($data['end']) && !empty($data['start']) && !empty($data['end'])) {
                        $workingHours[$day] = [
                            'start' => $data['start'],
                            'end' => $data['end']
                        ];
                    }
                }
                
                Log::info('Converted working hours from array format', [
                    'original' => $workingHoursArray,
                    'converted' => $workingHours
                ]);
            } else {
                $workingHours = json_decode($workingHoursJson, true);
                
                Log::info('Working hours validation', [
                    'raw_json' => $workingHoursJson,
                    'decoded' => $workingHours
                ]);
            }
            
            // Additional validation: ensure at least one day has working hours
            if (empty($workingHours) || (!is_array($workingHours) && !is_object($workingHours))) {
                return back()->withInput()->with('error', 'Working hours are required. Please set at least one day.');
            }
            
            // Count valid working days
            $validDays = 0;
            if (is_array($workingHours)) {
                foreach ($workingHours as $day => $hours) {
                    if (is_array($hours) && !empty($hours['start']) && !empty($hours['end'])) {
                        $validDays++;
                    }
                }
            }
            
            if ($validDays === 0) {
                return back()->withInput()->with('error', 'Working hours are required. Please set at least one day with start and end times.');
            }

            // 3. Prepare data for dentist creation
            $dentistData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'specialization' => $validatedData['specialization'],
                'bio' => $validatedData['bio'] ?? '',
                'working_hours' => $workingHours,
                'off_days' => isset($validatedData['off_days']) ? json_decode($validatedData['off_days'], true) : []
            ];

            // 4. Create dentist using SupabaseService
            $result = $this->supabase->createCompleteDentist($dentistData);

            if (!$result['success']) {
                return back()->withInput()->with('error', $result['error']);
            }

            $dentistId = $result['data']['id'];

            // 5. Handle avatar upload if present
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                if ($avatarFile->isValid()) {
                    $uploadResult = $this->supabase->uploadAvatarAsAdmin($dentistId, $avatarFile);
                    if (!$uploadResult['success']) {
                        return redirect()->route('admin.dentists.index')
                            ->with('warning', 'Dentist created but avatar upload failed: ' . $uploadResult['error']);
                    }
                } else {
                    return redirect()->route('admin.dentists.index')
                        ->with('warning', 'Dentist created but avatar could not be processed: File is not valid');
                }
            }

            Log::info('Dentist created successfully', ['dentist_id' => $dentistId]);
            return redirect()->route('admin.dentists.index')->with('success', 'Dentist created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating dentist in DentistController@store', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error creating dentist: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // Get complete dentist data including user profile information
            $result = $this->supabase->getCompleteDentist($id);
            
            if (!$result['success']) {
                return back()->with('error', 'Error fetching dentist: ' . $result['error']);
            }

                        $dentist = $result['data'];

            // Debug: Log the dentist data to check avatar
            Log::info('Dentist edit data', [
                'dentist_id' => $id,
                'avatar_in_response' => $dentist['avatar'] ?? 'NOT_SET',
                'avatar_type' => gettype($dentist['avatar'] ?? null),
                'all_keys' => array_keys($dentist)
            ]);

            // Ensure working_hours is properly formatted
            if (is_string($dentist['working_hours'])) {
                $dentist['working_hours'] = json_decode($dentist['working_hours'], true);
            }

            // Ensure off_days is properly formatted
            if (is_string($dentist['off_days'])) {
                $dentist['off_days'] = json_decode($dentist['off_days'], true);
            }

            return view('admin.dentists.edit', compact('dentist'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching dentist: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('DentistController@update started', ['dentist_id' => $id, 'request_data' => $request->all()]);
        
        try {
            // 1. Validate the request data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'specialization' => 'required|string|max:255',
                'bio' => 'nullable|string|max:1000',
                'avatar' => 'nullable|image|max:5120', // 5MB max
                'working_hours_json' => 'nullable|json',
                'off_days' => 'nullable|json'
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'phone.required' => 'Phone number is required.',
                'specialization.required' => 'Specialization is required.',
                'working_hours_json.json' => 'Working hours data is invalid.',
                'avatar.image' => 'Avatar must be an image file.',
                'avatar.max' => 'Avatar file size must not exceed 5MB.',
                'bio.max' => 'Bio must not exceed 1000 characters.',
            ]);

            // 2. Decode and validate working hours
            $workingHoursJson = $validatedData['working_hours_json'];
            
            // Check if working_hours_json is null but we have working_hours in the request
            if (is_null($workingHoursJson) && $request->has('working_hours')) {
                Log::info('working_hours_json is null, but working_hours field exists', [
                    'working_hours' => $request->input('working_hours')
                ]);
                
                // Convert the working_hours array to the correct format
                $workingHoursArray = $request->input('working_hours');
                $workingHours = [];
                
                foreach ($workingHoursArray as $day => $data) {
                    if (isset($data['start']) && isset($data['end']) && !empty($data['start']) && !empty($data['end'])) {
                        $workingHours[$day] = [
                            'start' => $data['start'],
                            'end' => $data['end']
                        ];
                    }
                }
                
                Log::info('Converted working hours from array format', [
                    'original' => $workingHoursArray,
                    'converted' => $workingHours
                ]);
            } else {
                $workingHours = json_decode($workingHoursJson, true);
                
                Log::info('Working hours validation', [
                    'raw_json' => $workingHoursJson,
                    'decoded' => $workingHours
                ]);
            }
            
            // Additional validation: ensure at least one day has working hours
            if (empty($workingHours) || (!is_array($workingHours) && !is_object($workingHours))) {
                return back()->withInput()->with('error', 'Working hours are required. Please set at least one day.');
            }
            
            // Count valid working days
            $validDays = 0;
            if (is_array($workingHours)) {
                foreach ($workingHours as $day => $hours) {
                    if (is_array($hours) && !empty($hours['start']) && !empty($hours['end'])) {
                        $validDays++;
                    }
                }
            }
            
            if ($validDays === 0) {
                return back()->withInput()->with('error', 'Working hours are required. Please set at least one day with start and end times.');
            }

            // 3. Update user profile data
            $userProfileData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone']
            ];

            Log::info('Updating user profile data', [
                'user_id' => $id,
                'data' => $userProfileData
            ]);

            try {
                $profileUpdateResult = $this->supabase->updateById('user_profiles', $id, $userProfileData);
                Log::info('User profile update result', [
                    'user_id' => $id,
                    'result' => $profileUpdateResult
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to update user profile', [
                    'user_id' => $id,
                    'error' => $e->getMessage()
                ]);
                return back()->withInput()->with('error', 'Failed to update user profile information: ' . $e->getMessage());
            }

            // 4. Update dentist data
            $dentistData = [
                'specialization' => $validatedData['specialization'],
                'bio' => $validatedData['bio'] ?? '',
                'working_hours' => $workingHours,
                'off_days' => isset($validatedData['off_days']) ? json_decode($validatedData['off_days'], true) : []
            ];

            Log::info('Updating dentist data', [
                'dentist_id' => $id,
                'data' => $dentistData
            ]);

            try {
                $dentistUpdateResult = $this->supabase->updateById('dentists', $id, $dentistData);
                Log::info('Dentist update result', [
                    'dentist_id' => $id,
                    'result' => $dentistUpdateResult
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to update dentist', [
                    'dentist_id' => $id,
                    'error' => $e->getMessage()
                ]);
                return back()->withInput()->with('error', 'Failed to update dentist information: ' . $e->getMessage());
            }

            // 5. Handle avatar upload if present
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                if ($avatarFile->isValid()) {
                    $uploadResult = $this->supabase->uploadAvatarAsAdmin($id, $avatarFile);
                    if (!$uploadResult['success']) {
                        return redirect()->route('admin.dentists.index')
                            ->with('warning', 'Dentist updated but avatar upload failed: ' . $uploadResult['error']);
                    }
                } else {
                    return redirect()->route('admin.dentists.index')
                        ->with('warning', 'Dentist updated but avatar could not be processed: File is not valid');
                }
            }

            Log::info('Dentist updated successfully', ['dentist_id' => $id]);
            return redirect()->route('admin.dentists.index')->with('success', 'Dentist updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating dentist in DentistController@update', [
                'dentist_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error updating dentist: ' . $e->getMessage());
        }   
    }

    public function destroy($id)
    {
        Log::info('DentistController@destroy started', ['dentist_id' => $id]);
        
        try {
            // Use the database function to delete complete dentist and all related data
            $result = $this->supabase->deleteCompleteDentist($id);

            if (!$result['success']) {
                Log::error('Failed to delete dentist using database function', [
                    'dentist_id' => $id,
                    'error' => $result['error']
                ]);
                return back()->with('error', 'Failed to delete dentist: ' . $result['error']);
            }

            $deletionData = $result['data'];
            $dentistName = $deletionData['dentist_name'] ?? 'Unknown';

            Log::info('Dentist deleted successfully using database function', [
                'dentist_id' => $id,
                'dentist_name' => $dentistName,
                'deletion_data' => $deletionData
            ]);

            return redirect()->route('admin.dentists.index')
                ->with('success', "Dentist {$dentistName} has been deleted successfully with all related data");

        } catch (\Exception $e) {
            Log::error('Error deleting dentist in DentistController@destroy', [
                'dentist_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error deleting dentist: ' . $e->getMessage());
        }
    }
}