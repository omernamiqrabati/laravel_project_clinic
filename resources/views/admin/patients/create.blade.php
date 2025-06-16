@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-3xl p-8 border border-gray-100">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b-2 border-gradient-to-r from-blue-400 to-purple-500 pb-4">
            <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                🦷 Add New Patient
            </span>
        </h2>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl mb-6 shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.patients.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Personal Info --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></div>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                        <input type="text" name="first_name" id="first_name"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400"
                            value="{{ old('first_name') }}" placeholder="Enter first name" required>
                    </div>
                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" id="last_name"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400"
                            value="{{ old('last_name') }}" placeholder="Enter last name" required>
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-teal-500 rounded-full mr-3"></div>
                    Contact Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400"
                            value="{{ old('email') }}" placeholder="patient@example.com" required>
                    </div>
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400"
                            value="{{ old('phone') }}" placeholder="+964 750 123 4567" required>
                    </div>
                </div>
            </div>

            {{-- Avatar --}}
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                <label for="avatar" class="block text-sm font-semibold text-gray-700 mb-4 flex items-center">
                    <div class="w-2 h-6 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full mr-3"></div>
                    Profile Photo
                </label>
                <input type="file" name="avatar" id="avatar"
                    class="w-full px-4 py-3 border-2 border-dashed border-purple-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 hover:border-purple-300 bg-white file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-500 file:text-white hover:file:bg-purple-600 file:shadow-md">
            </div>

            {{-- DOB / Gender / Address --}}
            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-2xl border border-orange-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-red-500 rounded-full mr-3"></div>
                    Additional Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white"
                            value="{{ old('date_of_birth') }}" required>
                    </div>
                    <div class="space-y-2">
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                        <select name="gender" id="gender"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white appearance-none bg-no-repeat bg-right pr-10" 
                            style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 4 5&quot;><path d=&quot;M2 0L0 2h4zm0 5L0 3h4z&quot; fill=&quot;%23666&quot;/></svg>'); background-position: right 1rem center; background-size: 0.65em;" required>
                            <option value="" class="text-gray-400">Select Gender</option>
                            <option value="Male" {{ old('gender')=='Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender')=='Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 hover:border-gray-300 bg-white placeholder-gray-400 resize-none"
                            placeholder="Enter full address" required>{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-6">
                <button type="submit"
                    class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center">
                        <svg class="h-5 w-5 mr-3 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" />
                        </svg>
                        Create Patient
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
