<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(SupabaseService $supabase): View
    {
        try {
            $products = $supabase->getProducts();
            Log::info('Products being passed to index view', ['products' => $products]);
            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in ProductController@index', [
                'error' => $e->getMessage()
            ]);
            return view('products.index', [
                'products' => [],
                'error' => 'Failed to fetch products. Please try again later.'
            ]);
        }
    }

    public function show(SupabaseService $supabase, $id): View
    {
        try {
            $product = $supabase->getProduct($id);
            Log::info('Product being passed to show view', [
                'id' => $id,
                'product' => $product
            ]);
            
            if (!$product) {
                return view('products.show', [
                    'error' => 'Product not found'
                ]);
            }
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            Log::error('Error in ProductController@show', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            return view('products.show', [
                'error' => 'Failed to fetch product. Please try again later.'
            ]);
        }
    }
}
