@extends('layouts.admin')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Edit Treatment</h1>
    <form action="{{ route('admin.treatments.update', $treatment['id']) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-sm font-semibold">Treatment Name</label>
            <input type="text" name="name" id="name" class="w-full border border-gray-300 px-4 py-2 rounded" value="{{ $treatment['name'] }}" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-semibold">Description</label>
            <textarea name="description" id="description" class="w-full border border-gray-300 px-4 py-2 rounded" required>{{ $treatment['description'] }}</textarea>
        </div>
        <div>
            <label for="cost" class="block text-sm font-semibold">Cost</label>
            <input type="number" name="cost" id="cost" class="w-full border border-gray-300 px-4 py-2 rounded" value="{{ $treatment['cost'] }}" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Treatment</button>
    </form>
@endsection
