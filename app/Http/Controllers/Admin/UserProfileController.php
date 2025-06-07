<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            $userProfiles = $this->supabase->fetchTable('user_profiles');
            
            return view('admin.user_profiles.index', [
                'userProfiles' => $userProfiles
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching user profiles: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.user_profiles.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'role' => 'required|string|in:patient,dentist,admin,staff',
                'avatar' => 'nullable|string|max:255',
                'email_verified' => 'boolean',
                'phone_verified' => 'boolean'
            ]);

            // Set default values for boolean fields if not provided
            $validatedData['email_verified'] = $validatedData['email_verified'] ?? false;
            $validatedData['phone_verified'] = $validatedData['phone_verified'] ?? false;

            $this->supabase->insert_table('user_profiles', $validatedData);

            return redirect()->route('admin.user_profiles.index')->with('success', 'User profile created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating user profile: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $userProfile = $this->supabase->fetchById('user_profiles', $id);
            return view('admin.user_profiles.edit', compact('userProfile'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching user profile: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'role' => 'required|string|in:patient,dentist,admin,staff',
                'avatar' => 'nullable|string|max:255',
                'email_verified' => 'boolean',
                'phone_verified' => 'boolean'
            ]);

            // Set default values for boolean fields if not provided
            $validatedData['email_verified'] = $validatedData['email_verified'] ?? false;
            $validatedData['phone_verified'] = $validatedData['phone_verified'] ?? false;

            $this->supabase->updateById('user_profiles', $id, $validatedData);

            return redirect()->route('admin.user_profiles.index')->with('success', 'User profile updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating user profile: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->supabase->deleteById('user_profiles', $id);

            return redirect()->route('admin.user_profiles.index')->with('success', 'User profile deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting user profile: ' . $e->getMessage());
        }
    }
}