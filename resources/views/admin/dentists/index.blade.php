@extends('layouts.admin')

@section('content')
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-blue-800 mb-6">🦷 Dental Clinic – Dentist Management</h2>

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

        {{-- Add Dentist Button --}}
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.dentists.create') }}"
               class="inline-block px-5 py-2.5 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                + Add New Dentist
            </a>
        </div>

        {{-- Dentist Table --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-md">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-blue-100 text-blue-800 text-sm font-semibold">
                    <tr>
                        <th class="px-5 py-3 border-b">ID</th>
                        <th class="px-5 py-3 border-b">Specialization</th>
                        <th class="px-5 py-3 border-b">Bio</th>
                        <th class="px-5 py-3 border-b">Working Hours</th>
                        <th class="px-5 py-3 border-b">Off Days</th>
                        <th class="px-5 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dentists as $dentist)
                        @php
                            $workingHours = is_string($dentist['working_hours']) ? json_decode($dentist['working_hours'], true) : $dentist['working_hours'];
                            $offDays = is_string($dentist['off_days']) ? json_decode($dentist['off_days'], true) : $dentist['off_days'];
                        @endphp

                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-5 py-3">{{ $dentist['dentist_id'] }}</td>
                            <td class="px-5 py-3">{{ $dentist['specialization'] }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $dentist['bio'] }}</td>

                            <td class="px-5 py-3">
                                @if(!empty($workingHours))
                                    <ul class="list-disc pl-4 space-y-1 text-xs text-gray-600">
                                        @foreach($workingHours as $wh)
                                            <li>
                                                <span class="font-medium">{{ $wh['day'] }}:</span>
                                                {{ $wh['start'] }} – {{ $wh['end'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400 italic">N/A</span>
                                @endif
                            </td>

                            <td class="px-5 py-3">
                                @if(!empty($offDays))
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        @foreach($offDays as $date)
                                            <li class="inline-block bg-gray-100 border border-gray-300 px-2 py-1 rounded">
                                                {{ $date }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400 italic">None</span>
                                @endif
                            </td>

                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('admin.dentists.edit', $dentist['dentist_id']) }}"
                                       class="text-yellow-600 hover:text-yellow-800 transition font-medium">Edit</a>

                                    <form action="{{ route('admin.dentists.destroy', $dentist['dentist_id']) }}"
                                          method="POST" onsubmit="return confirm('Are you sure you want to delete this dentist?');">
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
                            <td colspan="6" class="px-5 py-5 text-center text-gray-500">
                                No dentists found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
