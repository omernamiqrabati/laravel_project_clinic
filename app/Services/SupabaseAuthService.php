<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseAuthService
{
    protected $url;
    protected $anonKey;

    public function __construct()
    {
        $this->url = config('supabase.url');
        $this->anonKey = config('supabase.anon_key');
    }

    /**
     * Authenticate user with Supabase Auth
     */
    public function signInWithEmail($email, $password)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/auth/v1/token?grant_type=password', [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $authData = $response->json();
                
                // Get user profile data
                $userProfile = $this->getUserProfile($authData['user']['id']);
                
                return [
                    'success' => true,
                    'user' => $authData['user'],
                    'access_token' => $authData['access_token'],
                    'profile' => $userProfile,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['error_description'] ?? 'Authentication failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase authentication error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Authentication service unavailable',
            ];
        }
    }

    /**
     * Get user profile from user_profiles table
     */
    public function getUserProfile($userId)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/user_profiles', [
                'id' => 'eq.' . $userId,
                'select' => '*',
            ]);

            if ($response->successful()) {
                $profiles = $response->json();
                return !empty($profiles) ? $profiles[0] : null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Supabase get user profile error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Find user by email in user_profiles table
     */
    public function findUserByEmail($email)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/user_profiles', [
                'email' => 'eq.' . $email,
                'select' => '*',
            ]);

            if ($response->successful()) {
                $profiles = $response->json();
                return !empty($profiles) ? $profiles[0] : null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Supabase find user by email error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user has admin role
     */
    public function isAdminUser($userProfile)
    {
        if (!$userProfile) {
            return false;
        }

        return $userProfile['role'] === 'admin';
    }

    /**
     * Create a new user in Supabase (for testing purposes)
     */
    public function createUser($email, $password, $userData)
    {
        try {
            // First, create the auth user
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/auth/v1/signup', [
                'email' => $email,
                'password' => $password,
                'data' => $userData,
            ]);

            if ($response->successful()) {
                $authData = $response->json();
                $userId = $authData['user']['id'];

                // Then create the user profile
                $profileResponse = Http::withHeaders([
                    'apikey' => $this->anonKey,
                    'Authorization' => 'Bearer ' . $this->anonKey,
                    'Content-Type' => 'application/json',
                ])->post($this->url . '/rest/v1/user_profiles', [
                    'id' => $userId,
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email' => $email,
                    'role' => $userData['role']
                ]);

                if ($profileResponse->successful()) {
                    return [
                        'success' => true,
                        'user' => $authData['user'],
                        'message' => 'User created successfully',
                    ];
                }
            }

            return [
                'success' => false,
                'error' => $response->json()['error_description'] ?? 'User creation failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase create user error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'User creation service unavailable',
            ];
        }
    }

    /**
     * Send password reset email
     */
    public function resetPassword($email, $redirectTo = null)
    {
        try {
            $payload = ['email' => $email];
            
            if ($redirectTo) {
                $payload['options'] = ['redirectTo' => $redirectTo];
            }

            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/auth/v1/recover', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Password reset email sent successfully',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['error_description'] ?? 'Password reset failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase password reset error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Password reset service unavailable',
            ];
        }
    }

    /**
     * Update user password (when user is logged in)
     */
    public function updatePassword($accessToken, $newPassword)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->put($this->url . '/auth/v1/user', [
                'password' => $newPassword,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Password updated successfully',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['error_description'] ?? 'Password update failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase password update error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Password update service unavailable',
            ];
        }
    }
} 