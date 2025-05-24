@extends('layouts.admin')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Treatments</h1>
    <a href="{{ route('admin.treatments.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Treatment</a>
    <table class="table-auto w-full mt-6 border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Description</th>
                <th class="px-4 py-2 border">Cost</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($treatments as $treatment)
                <tr class="odd:bg-white even:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $treatment['name'] }}</td>
                    <td class="px-4 py-2 border">{{ $treatment['description'] }}</td>
                    <td class="px-4 py-2 border">${{ number_format($treatment['cost'], 2) }}</td>
                    <td class="px-4 py-2 border">
                        <a href="{{ route('admin.treatments.edit', $treatment['id']) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('admin.treatments.destroy', $treatment['id']) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
