<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Exception;

class ProductController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * List all products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $products = $this->supabase->fetchTable('products');
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a product by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            $deletedRecord = $this->supabase->deleteById('products', $id);
            return response()->json(['message' => 'Product deleted successfully', 'data' => $deletedRecord]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new product.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string'
            ]);

            $product = $this->supabase->insert('products', [
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'description' => $validatedData['description'] ?? null
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Insert sample products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertSampleProducts()
    {
        try {
            $sampleProducts = [
                [
                    'id'          => 1,
                    'created_at'  => '2025-04-01 10:15:00',
                    'name'        => 'Electric Toothbrush',
                    'price'       => 49.99,
                    'description' => 'A rechargeable electric toothbrush with two brushing modes.',
                ],
                [
                    'id'          => 2,
                    'created_at'  => '2025-03-25 14:30:00',
                    'name'        => 'Dental Floss Pack',
                    'price'       => 5.50,
                    'description' => 'Pack of 10 waxed dental floss sticks.',
                ],
                [
                    'id'          => 3,
                    'created_at'  => '2025-02-18 09:00:00',
                    'name'        => 'Mouthwash',
                    'price'       => 7.25,
                    'description' => '500ml antiseptic mouthwash, fresh mint flavor.',
                ],
            ];

            $insertedProducts = [];
            foreach ($sampleProducts as $product) {
                $insertedProduct = $this->supabase->insert('products', [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'created_at' => $product['created_at']
                ]);
                $insertedProducts[] = $insertedProduct;
            }

            return response()->json([
                'message' => 'Sample products inserted successfully',
                'data' => $insertedProducts
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Insert a product using URL parameters.
     *
     * @param string $name
     * @param string $price
     * @param string $description
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertWithParams($name, $price, $description)
    {
        try {
            // Convert price to float
            $price = floatval($price);

            $product = $this->supabase->insert('products', [
                'name' => urldecode($name),
                'price' => $price,
                'description' => urldecode($description)
            ]);

            return response()->json([
                'message' => 'Product inserted successfully',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a product by ID.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric|min:0',
                'description' => 'sometimes|nullable|string'
            ]);

            $updatedProduct = $this->supabase->updateById('products', $id, $validatedData);

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $updatedProduct
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
