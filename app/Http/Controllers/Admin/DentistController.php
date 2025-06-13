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
            // 1. Validate the request data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'specialization' => 'required|string|max:255',
                'bio' => 'nullable|string',
                'avatar' => 'nullable|image|max:5120', // 5MB max
                'email_verified' => 'nullable|boolean',
                'phone_verified' => 'nullable|boolean',
                'working_hours_json' => 'required|json',
                'off_days' => 'nullable|json'
            ]);

            // 2. Prepare data for dentist creation
            $dentistData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'specialization' => $validatedData['specialization'],
                'bio' => $validatedData['bio'] ?? '',
                'email_verified' => isset($validatedData['email_verified']),
                'phone_verified' => isset($validatedData['phone_verified']),
                'working_hours' => json_decode($validatedData['working_hours_json'], true),
                'off_days' => isset($validatedData['off_days']) ? json_decode($validatedData['off_days'], true) : []
            ];

            // 3. Create dentist using SupabaseService
            $result = $this->supabase->createCompleteDentist($dentistData);

            if (!$result['success']) {
                return back()->withInput()->with('error', $result['error']);
            }

            $dentistId = $result['data']['id'];

            // 4. Handle avatar upload if present
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                if ($avatarFile->isValid()) {
                    $uploadResult = $this->supabase->uploadUserAvatar($dentistId, $avatarFile);
                    if (!$uploadResult['success']) {
                        return redirect()->route('admin.dentists.index')
                            ->with('warning', 'Dentist created but avatar upload failed: ' . $uploadResult['error']);
                    }
                } else {
                    return redirect()->route('admin.dentists.index')
                        ->with('warning', 'Dentist created but avatar could not be processed: File is not valid');
                }
            }

            return redirect()->route('admin.dentists.index')->with('success', 'Dentist created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating dentist: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $dentist = $this->supabase->fetchById('dentists', $id);

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
        try {
            $validatedData = $request->validate([
                'specialization' => 'required|string|max:255',
                'bio' => 'nullable|string',
                'working_hours_json' => 'required|json',
                'off_days' => 'nullable|json'
            ]);

            $data = [
                'specialization' => $validatedData['specialization'],
                'bio' => $validatedData['bio'],
                'working_hours' => json_decode($validatedData['working_hours_json'], true),
                'off_days' => json_decode($validatedData['off_days'], true) ?? []
            ];

            $this->supabase->updateById('dentists', $id, $data);

            return redirect()->route('admin.dentists.index')
                ->with('success', 'Dentist updated successfully');
            } catch (\Exception $e) {
            return back()->with('error', 'Error updating dentist: ' . $e->getMessage());
        }   
    }
}