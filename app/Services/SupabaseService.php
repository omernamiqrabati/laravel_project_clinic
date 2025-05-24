<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('SUPABASE_URL');
        $this->apiKey = env('SUPABASE_KEY');

        if (!$this->baseUrl || !$this->apiKey) {
            throw new \Exception('Supabase configuration is missing. Please check your .env file.');
        }

        Log::info('Supabase Service Initialized', [
            'baseUrl' => $this->baseUrl,
            'apiKey' => substr($this->apiKey, 0, 10) . '...' // Only log first 10 chars of key for security
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
/**
 * Fetch all rows (optionally filtered) from any Supabase table.
 *
 * @param string $tableName
 * @param array  $queryParams e.g. ['select'=>'*', 'status'=>'eq.active']
 * @return array
 * @throws \Exception
 */
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
            $url = "{$this->baseUrl}/rest/v1/{$tableName}";
            $headers = [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ];

            Log::info("Making Supabase API request to insert data into table", [
                'table' => $tableName,
                'url' => $url,
                'headers' => array_keys($headers),
                'data' => $data
            ]);

            $response = Http::withHeaders($headers)->post($url, $data);

            Log::info("Supabase API response for insert table", [
                'table' => $tableName,
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::error("Supabase API error while inserting into table", [
                    'table' => $tableName,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Failed to insert data into table '{$tableName}' in Supabase");
            }

            $data = $response->json();
            Log::info("Supabase Insert Table Response", [
                'table' => $tableName,
                'status' => $response->status(),
                'data' => $data
            ]);

            return $data;
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
            $url = "{$this->baseUrl}/rest/v1/{$tableName}?patient_id=eq.{$id}"; // Updated to use 'patient_id' as the column name
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
            $url = "{$this->baseUrl}/rest/v1/{$tableName}?patient_id=eq.{$id}";
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
}
