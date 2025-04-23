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
}
