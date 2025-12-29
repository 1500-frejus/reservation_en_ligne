@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Organization settings</h1>

    @if(auth()->check() && (method_exists(auth()->user(), 'hasRole') && (auth()->user()->hasRole('organization_admin') || auth()->user()->hasRole('super_admin'))))
        <div class="mb-3">
            <a href="{{ route('admin.agents.index') }}" class="btn btn-primary">Gérer les agents</a>
            <a href="{{ route('admin.agents.invite') }}" class="btn btn-outline-primary">Inviter un agent</a>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.organization.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name', $org->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact email</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $org->contact_email) }}" class="form-control">
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
