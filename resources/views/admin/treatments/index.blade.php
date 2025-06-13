@extends('layouts.admin')

@section('content')
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-blue-800 mb-6">🦷 Dental Clinic – Treatment Management</h2>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 px-5 py-3 rounded-md bg-green-100 border border-green-400 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 px-5 py-3 rounded-md bg-red-100 border border-red-400 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Add Treatment Button --}}
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.treatments.create') }}"
               class="inline-block px-5 py-2.5 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                + Add New Treatment
            </a>
        </div>

        {{-- Treatment Table --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-md">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-blue-100 text-blue-800 text-sm font-semibold">
                    <tr>
                        <th class="px-5 py-3 border-b">ID</th>
                        <th class="px-5 py-3 border-b">Name</th>
                        <th class="px-5 py-3 border-b">Description</th>
                        <th class="px-5 py-3 border-b">Cost</th>
                        <th class="px-5 py-3 border-b">Duration (minutes)</th>
                        <th class="px-5 py-3 border-b">Status</th>
                        <th class="px-5 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($treatments as $treatment)
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-5 py-3" title="{{ $treatment['treatment_id'] }}">{{ substr($treatment['treatment_id'], 0, 8) }}...</td>
                            <td class="px-5 py-3 text-gray-900">{{ $treatment['name'] }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $treatment['description'] }}</td>
                            <td class="px-5 py-3 text-gray-900">${{ number_format($treatment['cost'], 2) }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $treatment['duration_minutes'] }} min</td>
                            <td class="px-5 py-3">
                                @if($treatment['is_active'])
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('admin.treatments.edit', $treatment['treatment_id']) }}"
                                       class="text-yellow-600 hover:text-yellow-800 transition font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.treatments.destroy', $treatment['treatment_id']) }}"
                                          method="POST" onsubmit="return confirm('Are you sure you want to delete this treatment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 transition font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-5 text-center text-gray-500">
                                No treatments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
