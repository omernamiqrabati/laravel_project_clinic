<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\UploadedFile;

class SupabaseService
{
    protected $baseUrl;
    protected $apiKey;
    protected $serviceKey;

    public function __construct()
    {
        $this->baseUrl = env('SUPABASE_URL');
        $this->apiKey = env('SUPABASE_KEY');
        $this->serviceKey = env('SUPABASE_SERVICE_KEY');

        if (!$this->baseUrl || !$this->apiKey) {
            throw new \Exception('Supabase configuration is missing. Please check your .env file.');
        }

        Log::info('Supabase Service Initialized', [
            'baseUrl' => $this->baseUrl,
            'apiKey' => substr($this->apiKey, 0, 10) . '...' // Only log first 10 chars of key for security
        ]);
    }

    protected function supabaseClient($usesServiceRole = false)
    {
        $key = $usesServiceRole ? $this->serviceKey : $this->apiKey;
        
        return Http::withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json',
            'Prefer' => 'return=minimal'
        ]);
    }

    public function getProducts()
    {
        try {
            $url = "{$this->baseUrl}/rest/v1/products";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ];
            $params = [
                'select' => '*',
            ];

            Log::info('Making Supabase API request', [
                'url' => $url,
                'headers' => array_keys($headers),
                'params' => $params
            ]);

            $response = Http::withHeaders($headers)->get($url, $params);

            Log::info('Supabase API response', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error('Supabase API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to fetch products from Supabase');
            }

            $data = $response->json();
            Log::info('Supabase Products Response', [
                'status' => $response->status(),
                'data' => $data
            ]);

            return $data;
        } catch (\Exception $e) {
            Log::error('Error fetching products from Supabase', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getProduct($id)
    {
        try {
            $url = "{$this->baseUrl}/rest/v1/products";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ];
            $params = [
                'select' => '*',
                'id' => 'eq.' . $id
            ];

            Log::info('Making Supabase API request for single product', [
                'url' => $url,
                'headers' => array_keys($headers),
                'params' => $params
            ]);

            $response = Http::withHeaders($headers)->get($url, $params);

            Log::info('Supabase API response for single product', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error('Supabase API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to fetch product from Supabase');
            }

            $data = $response->json();
            Log::info('Supabase Single Product Response', [
                'status' => $response->status(),
                'id' => $id,
                'data' => $data
            ]);

            return $data[0] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching product from Supabase', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            throw $e;
        }
    }

public function fetchTable(string $tableName, array $queryParams = [])
    {
        try {
            $url = "{$this->baseUrl}/rest/v1/{$tableName}";
            $headers = [
                'apikey'        => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ];
            // ensure at least a select
            $params = array_merge(['select' => '*'], $queryParams);

            Log::info("Supabase fetchTable request", [
                'table'       => $tableName,
                'url'         => $url,
                'headers'     => array_keys($headers),
                'queryParams' => $params,
            ]);

            $response = Http::withHeaders($headers)->get($url, $params);

            Log::info("Supabase fetchTable response", [
                'table'  => $tableName,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->failed()) {
                Log::error("Supabase fetchTable error", [
                    'table'  => $tableName,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                throw new \Exception("Failed to fetch table '{$tableName}' from Supabase");
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Error in fetchTable", [
                'table' => $tableName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function insert_table(string $tableName, array $data)
    {
        try {
            // Process any special fields that need conversion
            $processedData = $data;
            
            // Convert any arrays/objects to JSON strings for proper API handling
            foreach ($processedData as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $processedData[$key] = json_encode($value);
                }
            }
            
            $url = "{$this->baseUrl}/rest/v1/{$tableName}";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ];

            // Special handling for user_profiles table - need to use upsert in case of conflict
            if ($tableName === 'user_profiles' && isset($data['id'])) {
                $headers['Prefer'] = 'resolution=merge-duplicates,return=representation';
            }

            Log::info("Making Supabase API request to insert data into table", [
                'table' => $tableName,
                'url' => $url,
                'headers' => $headers,
                'data' => $processedData
            ]);

            $response = Http::withHeaders($headers)->post($url, $processedData);

            Log::info("Supabase API response for insert table", [
                'table' => $tableName,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error("Supabase API error while inserting into table", [
                    'table' => $tableName,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Failed to insert data into table '{$tableName}' in Supabase: " . $response->body());
            }

            $responseData = $response->json();
            Log::info("Supabase Insert Table Response", [
                'table' => $tableName,
                'status' => $response->status(),
                'data' => $responseData
            ]);

            return $responseData;
        } catch (\Exception $e) {
            Log::error("Error inserting data into table in Supabase", [
                'table' => $tableName,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function fetchById(string $tableName, $id)
    {
        try {
            $url = "{$this->baseUrl}/rest/v1/{$tableName}";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ];

            // Determine the primary key column dynamically
            $primaryKey = match ($tableName) {
                'patients' => 'patient_id',
                'dentists' => 'dentist_id',
                'appointments' => 'appointment_id',
                'receptionists' => 'receptionist_id',
                default => 'id',
            };

            $params = [
                'select' => '*',
                $primaryKey => 'eq.' . $id
            ];

            Log::info("Making Supabase API request to fetch by ID", [
                'table' => $tableName,
                'url' => $url,
                'headers' => array_keys($headers),
                'params' => $params
            ]);

            $response = Http::withHeaders($headers)->get($url, $params);

            Log::info("Supabase API response for fetch by ID", [
                'table' => $tableName,
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error("Supabase API error while fetching by ID", [
                    'table' => $tableName,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Failed to fetch record by ID from table '{$tableName}' in Supabase");
            }

            $data = $response->json();
            Log::info("Supabase Fetch By ID Response", [
                'table' => $tableName,
                'status' => $response->status(),
                'data' => $data
            ]);

            return $data[0] ?? null;
        } catch (\Exception $e) {
            Log::error("Error fetching record by ID from Supabase", [
                'table' => $tableName,
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            throw $e;
        }
    }

    public function deleteById(string $tableName, $id)
    {
        try {
            // Determine the primary key column dynamically
            $primaryKey = match ($tableName) {
                'patients' => 'patient_id',
                'dentists' => 'dentist_id',
                'appointments' => 'appointment_id',
                'receptionists' => 'receptionist_id',
                default => 'id',
            };
            
            $url = "{$this->baseUrl}/rest/v1/{$tableName}?{$primaryKey}=eq.{$id}";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ];

            Log::info("Making Supabase API request to delete record by ID", [
                'table' => $tableName,
                'url' => $url,
                'headers' => array_keys($headers),
                'id' => $id
            ]);

            $response = Http::withHeaders($headers)->delete($url);

            Log::info("Supabase API response for delete by ID", [
                'table' => $tableName,
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error("Supabase API error while deleting record by ID", [
                    'table' => $tableName,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Failed to delete record by ID in table '{$tableName}' in Supabase");
            }

            Log::info("Record deleted successfully", [
                'table' => $tableName,
                'id' => $id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Error deleting record by ID in Supabase", [
                'table' => $tableName,
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            throw $e;
        }
    }

    public function updateById(string $tableName, $id, array $data)
    {
        try {
            // Determine the primary key column dynamically
            $primaryKey = match ($tableName) {
                'patients' => 'patient_id',
                'dentists' => 'dentist_id',
                'appointments' => 'appointment_id',
                'receptionists' => 'receptionist_id',
                default => 'id',
            };
            
            $url = "{$this->baseUrl}/rest/v1/{$tableName}?{$primaryKey}=eq.{$id}";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ];

            Log::info("Supabase updateById request", [
                'table' => $tableName,
                'url' => $url,
                'headers' => array_keys($headers),
                'data' => $data,
            ]);

            $response = Http::withHeaders($headers)->patch($url, $data);

            Log::info("Supabase updateById response", [
                'table' => $tableName,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->failed()) {
                Log::error("Supabase updateById error", [
                    'table' => $tableName,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception("Failed to update record in table '{$tableName}'");
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Error in updateById", [
                'table' => $tableName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function fetchByQuery(string $tableName, array $filters, array $columns = ['*'])
    {
        try {
            $url = "{$this->baseUrl}/rest/v1/{$tableName}";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ];

            // Build query parameters
            $queryParams = ['select' => implode(',', $columns)];
            foreach ($filters as $key => $value) {
                $queryParams[$key] = "eq.{$value}";
            }

            Log::info("Making Supabase API request to fetch by query", [
                'table' => $tableName,
                'url' => $url,
                'headers' => array_keys($headers),
                'queryParams' => $queryParams
            ]);

            $response = Http::withHeaders($headers)->get($url, $queryParams);

            Log::info("Supabase API response for fetch by query", [
                'table' => $tableName,
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error("Supabase API error while fetching by query", [
                    'table' => $tableName,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Failed to fetch records by query from table '{$tableName}' in Supabase");
            }

            $data = $response->json();
            Log::info("Supabase Fetch By Query Response", [
                'table' => $tableName,
                'status' => $response->status(),
                'data' => $data
            ]);

            return $data;
        } catch (\Exception $e) {
            Log::error("Error fetching records by query from Supabase", [
                'table' => $tableName,
                'error' => $e->getMessage(),
                'filters' => $filters
            ]);
            throw $e;
        }
    }

    public function execute_sql(string $query)
    {
        try {
            $url = "{$this->baseUrl}/rest/v1/rpc/execute_sql";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ];

            $data = [
                'query' => $query
            ];

            Log::info("Making Supabase API request to execute SQL", [
                'url' => $url,
                'headers' => array_keys($headers),
                'query' => $query
            ]);

            $response = Http::withHeaders($headers)->post($url, $data);

            Log::info("Supabase API response for execute SQL", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error("Supabase API error while executing SQL", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Failed to execute SQL query in Supabase: " . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Error executing SQL in Supabase", [
                'error' => $e->getMessage(),
                'query' => $query
            ]);
            throw $e;
        }
    }

    public function createCompletePatient($data)
    {
        try {
            $response = $this->supabaseClient(true)
                ->post("{$this->baseUrl}/rest/v1/rpc/create_complete_patient", [
                    'p_first_name' => $data['first_name'],
                    'p_last_name' => $data['last_name'],
                    'p_email' => $data['email'],
                    'p_phone' => $data['phone'],
                    'p_date_of_birth' => $data['date_of_birth'] ?? null,
                    'p_gender' => $data['gender'] ?? null,
                    'p_address' => $data['address'] ?? null,
                    'p_email_verified' => isset($data['email_verified']) && $data['email_verified'],
                    'p_phone_verified' => isset($data['phone_verified']) && $data['phone_verified']
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Patient created successfully', ['response' => $responseData]);
                return [
                    'success' => true,
                    'data' => array_merge($data, ['id' => $responseData['id'] ?? null])
                ];
            }

            Log::error('Failed to create complete patient in Supabase', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to create complete patient in Supabase: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while creating complete patient in Supabase', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while creating complete patient in Supabase: ' . $e->getMessage()
            ];
        }
    }

    public function createCompleteDentist($data)
    {
        try {
            $response = $this->supabaseClient(true)
                ->post("{$this->baseUrl}/rest/v1/rpc/create_complete_dentist", [
                    'p_first_name' => $data['first_name'],
                    'p_last_name' => $data['last_name'],
                    'p_email' => $data['email'],
                    'p_phone' => $data['phone'],
                    'p_specialization' => $data['specialization'],
                    'p_bio' => $data['bio'] ?? null,
                    'p_working_hours' => $data['working_hours'] ?? null,
                    'p_off_days' => $data['off_days'] ?? null,
                    'p_email_verified' => isset($data['email_verified']) && $data['email_verified'],
                    'p_phone_verified' => isset($data['phone_verified']) && $data['phone_verified']
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Dentist created successfully', ['response' => $responseData]);
                return [
                    'success' => true,
                    'data' => array_merge($data, ['id' => $responseData['id'] ?? null])
                ];
            }

            Log::error('Failed to create complete dentist in Supabase', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to create complete dentist in Supabase: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while creating complete dentist in Supabase', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while creating complete dentist in Supabase: ' . $e->getMessage()
            ];
        }
    }

    public function createCompleteReceptionist($data)
    {
        try {
            $response = $this->supabaseClient(true)
                ->post("{$this->baseUrl}/rest/v1/rpc/create_complete_receptionist", [
                    'p_first_name' => $data['first_name'],
                    'p_last_name' => $data['last_name'],
                    'p_email' => $data['email'],
                    'p_phone' => $data['phone'],
                    'p_department' => $data['department'] ?? 'General',
                    'p_working_hours' => $data['working_hours'] ?? null,
                    'p_email_verified' => isset($data['email_verified']) && $data['email_verified'],
                    'p_phone_verified' => isset($data['phone_verified']) && $data['phone_verified']
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Receptionist created successfully', ['response' => $responseData]);
                return [
                    'success' => true,
                    'data' => array_merge($data, ['id' => $responseData['id'] ?? null])
                ];
            }

            Log::error('Failed to create complete receptionist in Supabase', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to create complete receptionist in Supabase: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while creating complete receptionist in Supabase', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while creating complete receptionist in Supabase: ' . $e->getMessage()
            ];
        }
    }

    public function uploadUserAvatar($userId, UploadedFile $file)
    {
        // Generate a unique file name for avatar 
        $fileName = 'avatar_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $bucketName = 'avatars';
        $folderPath = 'public'; // The folder within the bucket
        
        try {
            Log::info('Starting avatar upload to Supabase Storage', [
                'user_id' => $userId,
                'file_name' => $fileName,
                'bucket_name' => $bucketName,
                'folder_path' => $folderPath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize()
            ]);
            
            // Verify we have a valid file
            if (!$file->isValid()) {
                Log::error('Avatar file is not valid', [
                    'user_id' => $userId,
                    'error' => $file->getError()
                ]);
                return [
                    'success' => false,
                    'error' => 'Avatar file is not valid'
                ];
            }
            
            // Read file content directly
            $fileContent = file_get_contents($file->getRealPath());
            if ($fileContent === false) {
                Log::error('Failed to read avatar file content', [
                    'user_id' => $userId,
                    'file_path' => $file->getRealPath()
                ]);
                return [
                    'success' => false,
                    'error' => 'Failed to read avatar file content'
                ];
            }
            
            // Initialize cURL for direct upload
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "{$this->baseUrl}/storage/v1/object/{$bucketName}/{$folderPath}/{$fileName}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "apikey: {$this->serviceKey}",
                "Authorization: Bearer {$this->serviceKey}",
                "Content-Type: {$file->getMimeType()}",
                "Cache-Control: max-age=3600"
            ]);
            
            // Execute the upload request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            Log::info('Supabase storage upload response', [
                'user_id' => $userId,
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error
            ]);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                // Get the public URL for the avatar
                $publicUrl = "{$this->baseUrl}/storage/v1/object/public/{$bucketName}/{$folderPath}/{$fileName}";
                
                Log::info('Avatar uploaded successfully', [
                    'user_id' => $userId,
                    'file_path' => "{$bucketName}/{$folderPath}/{$fileName}",
                    'public_url' => $publicUrl
                ]);
                
                // Immediately update the user profile with the avatar URL
                $updateResult = $this->updateUserAvatarPath($userId, $publicUrl);
                
                if (!$updateResult['success']) {
                    Log::error('Avatar uploaded but failed to update profile', [
                        'user_id' => $userId,
                        'error' => $updateResult['error']
                    ]);
                    
                    return [
                        'success' => false,
                        'error' => 'The avatar was uploaded but failed to update your profile: ' . $updateResult['error']
                    ];
                }
                
                return [
                    'success' => true,
                    'path' => "{$bucketName}/{$folderPath}/{$fileName}",
                    'public_url' => $publicUrl
                ];
            }

            Log::error('Failed to upload avatar to Supabase Storage', [
                'user_id' => $userId,
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error
            ]);

            return [
                'success' => false,
                'error' => 'Failed to upload avatar to Supabase Storage: ' . ($error ?: $response)
            ];
        } catch (Exception $e) {
            Log::error('Exception while uploading avatar to Supabase Storage', [
                'user_id' => $userId,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'The avatar failed to upload: ' . $e->getMessage()
            ];
        }
    }

        public function updateUserAvatarPath($userId, $avatarUrl)
    {
        try {
            Log::info('Updating user avatar URL', [
                'user_id' => $userId,
                'avatar_url' => $avatarUrl
            ]);
            
            $url = "{$this->baseUrl}/rest/v1/user_profiles?id=eq.{$userId}";
            
            $response = Http::withHeaders([
                'apikey' => $this->serviceKey,
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->patch($url, [
                'avatar' => $avatarUrl
            ]);
            
            Log::info('Profile update response', [
                'status_code' => $response->status(),
                'response' => $response->body()
            ]);

            if ($response->successful()) {
                Log::info('Avatar URL updated successfully in user_profiles table', [
                    'user_id' => $userId,
                    'public_url' => $avatarUrl,
                    'response_data' => $response->json()
                ]);
                
                return [
                    'success' => true,
                    'public_url' => $avatarUrl
                ];
            }

            Log::error('Failed to update avatar path in user profile', [
                'statusCode' => $response->status(),
                'error' => $response->body(),
                'request_url' => $url,
                'user_id' => $userId
            ]);

            return [
                'success' => false,
                'error' => 'Failed to update avatar path in user profile: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while updating avatar path in user profile', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId
            ]);

            return [
                'success' => false,
                'error' => 'Exception while updating avatar path in user profile: ' . $e->getMessage()
            ];
        }
    }
}