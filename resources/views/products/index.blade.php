<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Products</h1>

        @if(isset($error))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if(isset($product['image_url']))
                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <h2 class="text-xl font-semibold mb-2">{{ $product['name'] }}</h2>
                        <p class="text-gray-600 mb-4">{{ $product['description'] }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold">${{ number_format($product['price'], 2) }}</span>
                            <a href="{{ route('products.show', $product['id']) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No products found.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
