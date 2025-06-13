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
    protected $url;
    protected $anonKey;

    public function __construct()
    {
        // Use config system for consistency
        $this->baseUrl = config('supabase.url');
        $this->url = config('supabase.url');
        $this->apiKey = config('supabase.anon_key');
        $this->anonKey = config('supabase.anon_key');
        $this->serviceKey = config('supabase.service_role_key');

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
                'treatments' => 'treatment_id',
                'invoices' => 'invoice_id',
                'payments' => 'payment_id',
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
                'treatments' => 'treatment_id',
                'invoices' => 'invoice_id',
                'payments' => 'payment_id',
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

    /**
     * Update a record in a Supabase table by ID.
     *
     * @param string $tableName
     * @param mixed $id
     * @param array $data
     * @return array|null
     * @throws \Exception
     */
    public function updateById(string $tableName, $id, array $data)
    {
        try {
            // Determine the primary key column dynamically
            $primaryKey = match ($tableName) {
                'patients' => 'patient_id',
                'dentists' => 'dentist_id',
                'appointments' => 'appointment_id',
                'receptionists' => 'receptionist_id',
                'treatments' => 'treatment_id',
                'invoices' => 'invoice_id',
                'payments' => 'payment_id',
                default => 'id',
            };

            $url = "{$this->baseUrl}/rest/v1/{$tableName}?{$primaryKey}=eq.{$id}";
            
            // Use service key for sensitive operations like user_profiles
            $useServiceKey = in_array($tableName, ['user_profiles', 'auth.users']);
            $authKey = $useServiceKey ? $this->serviceKey : $this->apiKey;
            
            $headers = [
                'apikey' => $authKey,
                'Authorization' => 'Bearer ' . $authKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ];

            // Add updated_at timestamp if the table supports it
            if (in_array($tableName, ['appointments', 'patients', 'treatments', 'user_profiles'])) {
                $data['updated_at'] = now()->toDateTimeString();
            }

            Log::info("Making Supabase API request to update record by ID", [
                'table' => $tableName,
                'url' => $url,
                'headers' => array_keys($headers),
                'id' => $id,
                'data' => $data
            ]);

            $response = Http::withHeaders($headers)->patch($url, $data);

            Log::info("Supabase API response for update by ID", [
                'table' => $tableName,
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error("Supabase API error while updating record by ID", [
                    'table' => $tableName,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $url,
                    'data' => $data,
                    'headers' => array_keys($headers)
                ]);
                throw new \Exception("Failed to update record by ID in table '{$tableName}' in Supabase: " . $response->body());
            }

            $responseData = $response->json();
            Log::info("Supabase Update By ID Response", [
                'table' => $tableName,
                'status' => $response->status(),
                'id' => $id,
                'data' => $responseData
            ]);

            // For updates, empty response or array might be valid (depending on Supabase's return preference)
            if ($response->successful()) {
                return is_array($responseData) && isset($responseData[0]) ? $responseData[0] : $responseData;
            } else {
                throw new \Exception("Update failed with status: " . $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Error updating record by ID in Supabase", [
                'table' => $tableName,
                'error' => $e->getMessage(),
                'id' => $id,
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update an appointment in the appointments table.
     *
     * @param string $appointmentId
     * @param array $data
     * @return array|null
     * @throws \Exception
     */
    public function updateAppointment(string $appointmentId, array $data)
    {
        try {
            // Validate appointment status if provided
            if (isset($data['status'])) {
                $validStatuses = ['arranged', 'in_appointment', 'completed', 'cancelled'];
                if (!in_array($data['status'], $validStatuses)) {
                    throw new \Exception("Invalid appointment status. Must be one of: " . implode(', ', $validStatuses));
                }
            }

            // Validate datetime fields if provided
            if (isset($data['start_time'])) {
                $data['start_time'] = date('c', strtotime($data['start_time'])); // Convert to ISO 8601
            }
            if (isset($data['end_time'])) {
                $data['end_time'] = date('c', strtotime($data['end_time'])); // Convert to ISO 8601
            }

            Log::info("Updating appointment", [
                'appointment_id' => $appointmentId,
                'update_data' => $data
            ]);

            $result = $this->updateById('appointments', $appointmentId, $data);

            if ($result) {
                Log::info("Appointment updated successfully", [
                    'appointment_id' => $appointmentId,
                    'updated_data' => $result
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("Error updating appointment", [
                'appointment_id' => $appointmentId,
                'error' => $e->getMessage(),
                'data' => $data
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
                    'p_address' => $data['address'] ?? null
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

    public function createCompleteReceptionist($data)
    {
        try {
            $response = $this->supabaseClient(true)
                ->post("{$this->baseUrl}/rest/v1/rpc/create_complete_receptionist", [
                    'p_first_name' => $data['first_name'],
                    'p_last_name' => $data['last_name'],
                    'p_email' => $data['email'],
                    'p_phone' => $data['phone'] ?? null
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Receptionist created successfully via function', ['response' => $responseData]);
                return [
                    'success' => true,
                    'data' => array_merge($data, ['id' => $responseData])
                ];
            }

            Log::error('Failed to create complete receptionist via function', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to create complete receptionist: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while creating complete receptionist via function', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while creating complete receptionist: ' . $e->getMessage()
            ];
        }
    }

    public function createCompleteDentist($data)
    {
        try {
            // Generate unique UUID for the dentist
            $dentistId = \Illuminate\Support\Str::uuid();
            
            $response = $this->supabaseClient(true)
                ->post("{$this->baseUrl}/rest/v1/rpc/store_dentist_with_user", [
                    'p_first_name' => $data['first_name'],
                    'p_last_name' => $data['last_name'],
                    'p_email' => $data['email'],
                    'p_phone' => $data['phone'] ?? null,
                    'p_specialization' => $data['specialization'] ?? null,
                    'p_bio' => $data['bio'] ?? null,
                    'p_working_hours' => isset($data['working_hours']) ? $data['working_hours'] : null,
                    'p_off_days' => $data['off_days'] ?? null,
                    'p_dentist_id' => $dentistId,
                    'p_avatar' => $data['avatar'] ?? null
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Dentist created successfully', ['response' => $responseData]);
                return [
                    'success' => true,
                    'data' => array_merge($data, ['id' => $responseData])
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



    /**
     * Create dentist from existing user profile
     */
    public function createDentistFromProfile($userProfileId, $data)
    {
        try {
            $response = $this->supabaseClient(true)
                ->post("{$this->baseUrl}/rest/v1/rpc/create_dentist_from_profile", [
                    'user_profile_id' => $userProfileId,
                    'p_specialization' => $data['specialization'] ?? null,
                    'p_bio' => $data['bio'] ?? null,
                    'p_working_hours' => isset($data['working_hours']) ? json_encode($data['working_hours']) : null,
                    'p_off_days' => $data['off_days'] ?? null
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Dentist created from profile successfully', ['response' => $responseData]);
                return [
                    'success' => $responseData['success'] ?? true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to create dentist from profile in Supabase', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to create dentist from profile: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while creating dentist from profile', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while creating dentist from profile: ' . $e->getMessage()
            ];
        }
    }



    /**
     * Get complete dentist information
     */
    public function getCompleteDentist($dentistId)
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/get_complete_dentist", [
                    'dentist_uuid' => $dentistId
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete dentist data retrieved', ['dentist_id' => $dentistId]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to get complete dentist data', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to get complete dentist data: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while getting complete dentist data', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while getting complete dentist data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * List all dentists with complete information
     */
    public function listAllDentists()
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/list_all_dentists", []);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('All dentists data retrieved', ['count' => count($responseData)]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to get all dentists data', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to get all dentists data: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while getting all dentists data', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while getting all dentists data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete complete dentist and all related data
     */
    public function deleteCompleteDentist($dentistId)
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/delete_complete_dentist", [
                    'dentist_uuid' => $dentistId
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete dentist deletion response', ['dentist_id' => $dentistId, 'response' => $responseData]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to delete complete dentist', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to delete complete dentist: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while deleting complete dentist', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while deleting complete dentist: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete complete patient and all related data
     */
    public function deleteCompletePatient($patientId)
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/delete_complete_patient", [
                    'patient_uuid' => $patientId
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete patient deletion response', ['patient_id' => $patientId, 'response' => $responseData]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to delete complete patient', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to delete complete patient: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while deleting complete patient', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while deleting complete patient: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get complete patient with user profile data
     */
    public function getCompletePatient($patientId)
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/get_complete_patient", [
                    'patient_uuid' => $patientId
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete patient data retrieved', ['patient_id' => $patientId, 'response' => $responseData]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to get complete patient', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to get complete patient: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while getting complete patient', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while getting complete patient: ' . $e->getMessage()
            ];
        }
    }

    /**
     * List all patients with complete information
     */
    public function listAllPatients()
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/list_all_patients", []);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('All patients data retrieved', ['count' => count($responseData)]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to get all patients data', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to get all patients data: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while getting all patients data', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while getting all patients data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update complete patient data using database function
     */
    public function updateCompletePatient($patientId, $data)
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/update_complete_patient", [
                    'patient_uuid' => $patientId,
                    'p_first_name' => $data['first_name'],
                    'p_last_name' => $data['last_name'],
                    'p_email' => $data['email'],
                    'p_phone' => $data['phone'],
                    'p_date_of_birth' => $data['date_of_birth'],
                    'p_gender' => $data['gender'],
                    'p_address' => $data['address']
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete patient update response', ['patient_id' => $patientId, 'response' => $responseData]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to update complete patient', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to update complete patient: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while updating complete patient', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while updating complete patient: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update complete patient data including avatar using database function
     */
    public function updateCompletePatientWithAvatar($patientId, $data)
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/update_patient_with_avatar", [
                    'patient_uuid' => $patientId,
                    'p_first_name' => $data['first_name'],
                    'p_last_name' => $data['last_name'],
                    'p_email' => $data['email'],
                    'p_phone' => $data['phone'],
                    'p_date_of_birth' => $data['date_of_birth'],
                    'p_gender' => $data['gender'],
                    'p_address' => $data['address'],
                    'p_avatar_url' => $data['avatar_url']
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete patient update with avatar response', ['patient_id' => $patientId, 'response' => $responseData]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to update complete patient with avatar', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to update complete patient with avatar: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while updating complete patient with avatar', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while updating complete patient with avatar: ' . $e->getMessage()
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

    /**
     * Get complete receptionist with user profile data
     */
    public function getCompleteReceptionist($receptionistId)
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/get_complete_receptionist", [
                    'user_id_param' => $receptionistId
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete receptionist data retrieved', ['receptionist_id' => $receptionistId]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to get complete receptionist data', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to get complete receptionist data: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while getting complete receptionist data', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while getting complete receptionist data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * List all receptionists with complete information
     */
    public function listAllReceptionists()
    {
        try {
            $response = $this->supabaseClient()
                ->post("{$this->baseUrl}/rest/v1/rpc/list_all_receptionists", []);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('All receptionists data retrieved', ['count' => count($responseData)]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to get all receptionists data', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to get all receptionists data: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while getting all receptionists data', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while getting all receptionists data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update complete receptionist data
     */
    public function updateCompleteReceptionist($receptionistId, $data)
    {
        try {
            $response = $this->supabaseClient(true)
                ->post("{$this->baseUrl}/rest/v1/rpc/update_complete_receptionist", [
                    'user_id_param' => $receptionistId,
                    'updates' => $data
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete receptionist update response', ['receptionist_id' => $receptionistId, 'response' => $responseData]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to update complete receptionist', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to update complete receptionist: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while updating complete receptionist', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while updating complete receptionist: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete complete receptionist and all related data
     */
    public function deleteCompleteReceptionist($receptionistId)
    {
        try {
            $response = $this->supabaseClient(true)
                ->post("{$this->baseUrl}/rest/v1/rpc/delete_complete_receptionist", [
                    'user_id_param' => $receptionistId
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Complete receptionist deletion response', ['receptionist_id' => $receptionistId, 'response' => $responseData]);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            Log::error('Failed to delete complete receptionist', [
                'statusCode' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to delete complete receptionist: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception while deleting complete receptionist', [
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception while deleting complete receptionist: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Insert a new record into a Supabase table.
     *
     * @param string $tableName
     * @param array $data
     * @return array|null
     * @throws \Exception
     */
    public function insert(string $tableName, array $data)
    {
        try {
            $url = "{$this->baseUrl}/rest/v1/{$tableName}";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ];

            Log::info("Supabase insert request", [
                'table' => $tableName,
                'url' => $url,
                'headers' => array_keys($headers),
                'data' => $data,
            ]);

            $response = Http::withHeaders($headers)->post($url, [$data]);

            Log::info("Supabase insert response", [
                'table' => $tableName,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->failed()) {
                Log::error("Supabase insert error", [
                    'table' => $tableName,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception("Failed to insert record into table '{$tableName}'");
            }

            $json = $response->json();
            return is_array($json) && isset($json[0]) ? $json[0] : $json;
        } catch (\Exception $e) {
            Log::error("Error in insert", [
                'table' => $tableName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
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
     * Get all users with specific role
     */
    public function getUsersByRole($role)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/user_profiles', [
                'role' => 'eq.' . $role,
                'select' => '*',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Supabase get users by role error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Upload avatar with explicit service role authentication for admin operations
     */
    public function uploadAvatarAsAdmin($userId, UploadedFile $file)
    {
        // Generate a unique file name for avatar 
        $fileName = 'avatar_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $bucketName = 'avatars';
        $folderPath = 'public';
        
        try {
            Log::info('Starting admin avatar upload to Supabase Storage', [
                'user_id' => $userId,
                'file_name' => $fileName,
                'bucket_name' => $bucketName,
                'using_service_role' => true,
                'service_key_present' => !empty($this->serviceKey),
                'service_key_length' => strlen($this->serviceKey ?? ''),
                'base_url' => $this->baseUrl
            ]);
            
            // Verify we have a valid file
            if (!$file->isValid()) {
                return [
                    'success' => false,
                    'error' => 'Avatar file is not valid'
                ];
            }
            
            // Read file content
            $fileContent = file_get_contents($file->getRealPath());
            if ($fileContent === false) {
                return [
                    'success' => false,
                    'error' => 'Failed to read avatar file content'
                ];
            }
            
            $headers = [
                'apikey' => $this->serviceKey,
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => $file->getMimeType(),
                'Cache-Control' => 'max-age=3600'
            ];
            
            $uploadUrl = "{$this->baseUrl}/storage/v1/object/{$bucketName}/{$folderPath}/{$fileName}";
            
            Log::info('Making storage upload request', [
                'url' => $uploadUrl,
                'headers' => array_keys($headers),
                'content_type' => $file->getMimeType(),
                'file_size' => strlen($fileContent)
            ]);
            
            // Use service role with explicit authentication
            $response = Http::withHeaders($headers)
                ->withBody($fileContent, $file->getMimeType())
                ->put($uploadUrl);
            
            Log::info('Admin avatar upload response', [
                'user_id' => $userId,
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'response_headers' => $response->headers()
            ]);
            
            if ($response->successful()) {
                $publicUrl = "{$this->baseUrl}/storage/v1/object/public/{$bucketName}/{$folderPath}/{$fileName}";
                
                Log::info('Avatar upload successful', [
                    'user_id' => $userId,
                    'public_url' => $publicUrl
                ]);
                
                return [
                    'success' => true,
                    'path' => "{$bucketName}/{$folderPath}/{$fileName}",
                    'public_url' => $publicUrl
                ];
            }

            Log::error('Avatar upload failed', [
                'user_id' => $userId,
                'status_code' => $response->status(),
                'error_body' => $response->body(),
                'url' => $uploadUrl
            ]);

            return [
                'success' => false,
                'error' => 'Failed to upload avatar: ' . $response->body()
            ];
        } catch (Exception $e) {
            Log::error('Exception during admin avatar upload', [
                'user_id' => $userId,
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Avatar upload failed: ' . $e->getMessage()
            ];
        }
    }
}