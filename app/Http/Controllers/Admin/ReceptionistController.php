<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
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
            $receptionists = $this->supabase->fetchTable('receptionists');

            // Convert complex fields to strings for display
            $receptionists = array_map(function ($receptionist) {
                $receptionist['working_hours'] = is_array($receptionist['working_hours']) ? json_encode($receptionist['working_hours']) : $receptionist['working_hours'];
                return $receptionist;
            }, $receptionists);

            return view('admin.receptionists.index', ['receptionists' => $receptionists]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching receptionists: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.receptionists.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'department' => 'required|string',
                'working_hours_json' => 'required|json',
                'email_verified' => 'nullable|boolean',
                'phone_verified' => 'nullable|boolean',
                'avatar' => 'nullable|image|max:5120' // Validate image upload
            ]);

            // Prepare data for receptionist creation
            $receptionistData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'department' => $validatedData['department'],
                'working_hours' => json_decode($validatedData['working_hours_json'], true),
                'email_verified' => isset($validatedData['email_verified']),
                'phone_verified' => isset($validatedData['phone_verified'])
            ];

            // Create receptionist using RPC function
            $result = $this->supabase->createCompleteReceptionist($receptionistData);
            
            if (!$result['success']) {
                return back()->withInput()->with('error', $result['error']);
            }

            // Get the ID of the newly created receptionist
            $receptionistId = $result['data']['id'];
            
            Log::info('Receptionist created successfully, now handling avatar', [
                'receptionist_id' => $receptionistId
            ]);

            // Handle file upload if avatar is present
            if ($request->hasFile('avatar')) {
                Log::info('Avatar file is present in the request', [
                    'receptionist_id' => $receptionistId,
                    'file_name' => $request->file('avatar')->getClientOriginalName(),
                    'file_size' => $request->file('avatar')->getSize()
                ]);
                
                // Upload avatar to Supabase storage
                $uploadResult = $this->supabase->uploadUserAvatar($receptionistId, $request->file('avatar'));
                
                // The uploadUserAvatar method now updates the profile directly
                if (!$uploadResult['success']) {
                    Log::error('Failed to upload avatar', [
                        'receptionist_id' => $receptionistId,
                        'error' => $uploadResult['error']
                    ]);
                }
            }

            return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist created successfully');
        } catch (\Exception $e) {
            Log::error('Error in ReceptionistController@store', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error creating receptionist: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $receptionist = $this->supabase->fetchById('receptionists', $id);

            // Decode working_hours JSON string to array
            $receptionist['working_hours'] = is_string($receptionist['working_hours']) ? json_decode($receptionist['working_hours'], true) : $receptionist['working_hours'];

            return view('admin.receptionists.edit', compact('receptionist'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching receptionist: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'department' => 'required|string',
                'working_hours_json' => 'required|json'
            ]);

            $updateData = [
                'department' => $validatedData['department'],
                'working_hours' => json_decode($validatedData['working_hours_json'], true),
                'updated_at' => now()
            ];

            $this->supabase->updateById('receptionists', $id, $updateData);
            
            return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating receptionist: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->supabase->deleteById('receptionists', $id);
            return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting receptionist: ' . $e->getMessage());
        }
    }
} 