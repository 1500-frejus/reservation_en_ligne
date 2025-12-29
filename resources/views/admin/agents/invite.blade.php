@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Invite Agent</h1>

    <form method="POST" action="{{ route('admin.agents.invite.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Full name (optional)</label>
            <input type="text" name="name" class="form-control">
        </div>

        <button class="btn btn-primary">Send invitation</button>
    </form>
</div>
@endsection
