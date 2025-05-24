<div class="grid grid-cols-2 gap-4">
    <x-select name="patient_id" label="Patient" :options="$patients->pluck('full_name','patient_id')" :selected="\$appointment['patient_id'] ?? null" />
    <x-select name="dentist_id" label="Dentist" :options="$dentists->pluck('specialization','dentist_id')" :selected="\$appointment['dentist_id'] ?? null" />
    <x-select name="treatment_id" label="Treatment" :options="$treatments->pluck('name','treatment_id')" :selected="\$appointment['treatment_id'] ?? null" />
</div>

<div class="grid grid-cols-2 gap-4">
    <x-input type="datetime-local" name="start_time" label="Start Time" :value="\$appointment['start_time'] ?? ''" />
    <x-input type="datetime-local" name="end_time" label="End Time" :value="\$appointment['end_time'] ?? ''" />
</div>

<x-select name="status" label="Status" :options="array_combine(\$statuses,\$statuses)" :selected="\$appointment['status'] ?? null" />

<x-textarea name="notes" label="Notes">{{ \$appointment['notes'] ?? '' }}</x-textarea>

<div x-data="{status: '{{ \$appointment['status'] ?? 'Scheduled' }}'}">
    <template x-if="status === 'Cancelled'">
        <x-textarea name="cancellation_reason" label="Cancellation Reason">{{ \$appointment['cancellation_reason'] ?? '' }}</x-textarea>
    </template>
    <template x-if="status === 'Rescheduled'">
        <x-textarea name="reschedule_reason" label="Reschedule Reason">{{ \$appointment['reschedule_reason'] ?? '' }}</x-textarea>
    </template>
</div>

<form action="{{ route('appointments.destroy', $appointment['appointment_id']) }}"