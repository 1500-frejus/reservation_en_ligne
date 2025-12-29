@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Accept invitation</h1>

    <form method="POST" action="{{ route('invitations.accept.post', ['token' => $invitation->token]) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="name" value="{{ old('name', $invitation->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button class="btn btn-primary">Create account</button>
    </form>
</div>
@endsection
