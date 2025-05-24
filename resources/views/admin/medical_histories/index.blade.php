@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Medical Histories</h1>
        <a href="{{ route('admin.medical_histories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Record</a>
        <table class="table-auto w-full mt-6 border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Patient Name</th>
                    <th class="px-4 py-2 border">Diagnosis</th>
                    <th class="px-4 py-2 border">Treatment</th>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($medicalHistories as $history)
                    <tr class="odd:bg-white even:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $history['patient_name'] }}</td>
                        <td class="px-4 py-2 border">{{ $history['diagnosis'] }}</td>
                        <td class="px-4 py-2 border">{{ $history['treatment'] }}</td>
                        <td class="px-4 py-2 border">{{ $history['date'] }}</td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('admin.medical_histories.edit', $history['id']) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('admin.medical_histories.destroy', $history['id']) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
