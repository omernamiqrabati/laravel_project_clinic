<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UniqueSupabaseEmail implements ValidationRule
{
    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $baseUrl = config('services.supabase.url');
            $anonKey = config('services.supabase.key');

            if (!$baseUrl || !$anonKey) {
                Log::error('Supabase configuration missing', [
                    'base_url' => $baseUrl ? 'present' : 'missing',
                    'anon_key' => $anonKey ? 'present' : 'missing'
                ]);
                $fail('Unable to validate email uniqueness - configuration error');
                return;
            }

            $url = "{$baseUrl}/rest/v1/user_profiles?email=eq.{$value}&select=id";
            
            $response = Http::withHeaders([
                'apikey' => $anonKey,
                'Authorization' => "Bearer {$anonKey}",
                'Content-Type' => 'application/json'
            ])->get($url);

            if (!$response->successful()) {
                Log::error('Failed to check email uniqueness via Supabase API', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'email' => $value
                ]);
                $fail('Unable to validate email uniqueness - API error');
                return;
            }

            $existingUsers = $response->json();
            
            // If we're updating and ignoring a specific ID, filter it out
            if ($this->ignoreId && is_array($existingUsers)) {
                $existingUsers = array_filter($existingUsers, function($user) {
                    return $user['id'] !== $this->ignoreId;
                });
            }

            if (!empty($existingUsers)) {
                $fail('The email address is already registered.');
            }

        } catch (\Exception $e) {
            Log::error('Exception during email uniqueness validation', [
                'error' => $e->getMessage(),
                'email' => $value
            ]);
            $fail('Unable to validate email uniqueness - system error');
        }
    }
}
