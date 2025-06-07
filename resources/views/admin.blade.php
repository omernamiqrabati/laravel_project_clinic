@extends('layouts.admin')

@section('title', 'Admin Panel')

@section('content')
<script>
    // Redirect to dashboard
    window.location.href = "{{ route('admin.dashboard') }}";
</script>

<div class="text-center py-12">
    <p class="text-gray-600">Redirecting to dashboard...</p>
</div>
@endsection