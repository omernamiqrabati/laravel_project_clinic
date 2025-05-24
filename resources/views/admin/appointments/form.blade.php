@php
    $action = isset($appointment) ? route('admin.appointments.update', $appointment['appointment_id']) : route('admin.appointments.store');
    $method = isset($appointment) ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" class="space-y-4">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    @include('admin.appointments.fields')

    <button type="submit" class="btn-primary">{{ isset($appointment) ? 'Update' : 'Create' }} Appointment</button>
</form>