<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <a href="/" class="inline-block mb-8 text-blue-500 hover:text-blue-600">
            ← Back to Products
        </a>

        @if(isset($error))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="md:flex">
                    @if(isset($product['image_url']))
                        <div class="md:w-1/2">
                            <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="w-full h-96 object-cover">
                        </div>
                    @endif
                    <div class="p-8 md:w-1/2">
                        <h1 class="text-3xl font-bold mb-4">{{ $product['name'] }}</h1>
                        <p class="text-gray-600 mb-6">{{ $product['description'] }}</p>
                        <div class="flex items-center mb-6">
                            <span class="text-2xl font-bold">${{ number_format($product['price'], 2) }}</span>
                        </div>
                        @if(isset($product['stock']))
                            <div class="mb-6">
                                <span class="text-sm font-semibold text-gray-600">Stock:</span>
                                <span class="ml-2 {{ $product['stock'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                        @endif
                        @if(isset($product['category']))
                            <div class="mb-6">
                                <span class="text-sm font-semibold text-gray-600">Category:</span>
                                <span class="ml-2">{{ $product['category'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html> 