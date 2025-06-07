@extends('layouts.admin')

@section('title', 'User Profiles')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-800">🦷 Dental Clinic – User Profiles</h1>
                <a href="{{ route('admin.user_profiles.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    + Add New User Profile
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 px-5 py-3 rounded-md bg-green-100 border border-green-400 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 px-5 py-3 rounded-md bg-red-100 border border-red-400 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="p-6">
            @if(isset($userProfiles) && count($userProfiles) > 0)
                {{-- Mobile Card View --}}
                <div class="block md:hidden space-y-4">
                    @foreach($userProfiles as $userProfile)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">
                                            {{ strtoupper(substr($userProfile['first_name'], 0, 1) . substr($userProfile['last_name'], 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $userProfile['first_name'] }} {{ $userProfile['last_name'] }}</h3>
                                        <p class="text-sm text-gray-600">{{ $userProfile['email'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'dentist' => 'bg-blue-100 text-blue-800',
                                            'staff' => 'bg-yellow-100 text-yellow-800',
                                            'patient' => 'bg-green-100 text-green-800'
                                        ];
                                        $roleColor = $roleColors[$userProfile['role']] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $roleColor }}">
                                        {{ ucfirst($userProfile['role']) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-3 space-y-1">
                                <p class="text-sm text-gray-600">📞 {{ $userProfile['phone'] }}</p>
                                <div class="flex space-x-4">
                                    <span class="text-xs {{ $userProfile['email_verified'] ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $userProfile['email_verified'] ? '✓ Email Verified' : '✗ Email Not Verified' }}
                                    </span>
                                    <span class="text-xs {{ $userProfile['phone_verified'] ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $userProfile['phone_verified'] ? '✓ Phone Verified' : '✗ Phone Not Verified' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.user_profiles.edit', $userProfile['id']) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('admin.user_profiles.destroy', $userProfile['id']) }}"
                                      method="POST" onsubmit="return confirm('Are you sure?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-blue-50 border-b border-blue-200">
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Contact</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Role</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Verification</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($userProfiles as $userProfile)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-4 py-4 text-sm">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-blue-600 font-semibold text-xs">
                                                    {{ strtoupper(substr($userProfile['first_name'], 0, 1) . substr($userProfile['last_name'], 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $userProfile['first_name'] }} {{ $userProfile['last_name'] }}</p>
                                                <p class="text-gray-600 text-xs">ID: #{{ $userProfile['id'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <div>
                                            <p class="font-medium">{{ $userProfile['email'] }}</p>
                                            <p class="text-gray-600">{{ $userProfile['phone'] }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-red-100 text-red-800',
                                                'dentist' => 'bg-blue-100 text-blue-800',
                                                'staff' => 'bg-yellow-100 text-yellow-800',
                                                'patient' => 'bg-green-100 text-green-800'
                                            ];
                                            $roleColor = $roleColors[$userProfile['role']] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $roleColor }}">
                                            {{ ucfirst($userProfile['role']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <div class="space-y-1">
                                            <div class="flex items-center space-x-1">
                                                <span class="w-2 h-2 rounded-full {{ $userProfile['email_verified'] ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                                <span class="text-xs {{ $userProfile['email_verified'] ? 'text-green-600' : 'text-red-600' }}">
                                                    Email
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <span class="w-2 h-2 rounded-full {{ $userProfile['phone_verified'] ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                                <span class="text-xs {{ $userProfile['phone_verified'] ? 'text-green-600' : 'text-red-600' }}">
                                                    Phone
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.user_profiles.edit', $userProfile['id']) }}"
                                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.user_profiles.destroy', $userProfile['id']) }}"
                                                  method="POST" onsubmit="return confirm('Are you sure you want to delete this user profile?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-800 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-blue-50 rounded-lg p-8 max-w-md mx-auto">
                        <div class="text-blue-600 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-blue-800 mb-2">No user profiles found</h3>
                        <p class="text-blue-600 mb-4">Get started by creating your first user profile.</p>
                        <a href="{{ route('admin.user_profiles.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Create User Profile
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection