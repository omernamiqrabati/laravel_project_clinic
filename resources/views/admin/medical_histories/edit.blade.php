@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">{{ isset($medicalHistory) ? 'Edit' : 'Add New' }} Medical History</h1>
        <form action="{{ isset($medicalHistory) ? route('admin.medical_histories.update', $medicalHistory['id']) : route('admin.medical_histories.store') }}" method="POST" class="space-y-4">
            @csrf
            @if (isset($medicalHistory))
                @method('PUT')
            @endif

            <div>
                <label for="invoice_id" class="block text-sm font-semibold">Invoice ID</label>
                <input type="number" name="invoice_id" id="invoice_id" value="{{ old('invoice_id', $medicalHistory['invoice_id'] ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <div>
                <label for="patient_name" class="block text-sm font-semibold">Patient Name</label>
                <input type="text" name="patient_name" id="patient_name" value="{{ old('patient_name', $medicalHistory['patient_name'] ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <div>
                <label for="diagnosis" class="block text-sm font-semibold">Diagnosis</label>
                <input type="text" name="diagnosis" id="diagnosis" value="{{ old('diagnosis', $medicalHistory['diagnosis'] ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <div>
                <label for="treatment" class="block text-sm font-semibold">Treatment</label>
                <input type="text" name="treatment" id="treatment" value="{{ old('treatment', $medicalHistory['treatment'] ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <div>
                <label for="date" class="block text-sm font-semibold">Date</label>
                <input type="date" name="date" id="date" value="{{ old('date', $medicalHistory['date'] ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded" required>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">{{ isset($medicalHistory) ? 'Update' : 'Save' }} Medical History</button>
        </form>
    </div>
@endsection
