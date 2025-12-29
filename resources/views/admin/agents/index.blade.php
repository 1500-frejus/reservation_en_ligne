@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agents</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.agents.create') }}" class="btn btn-primary mb-3">Add agent</a>
    <a href="{{ route('admin.agents.invite') }}" class="btn btn-secondary mb-3 ms-2">Invite agent</a>

    <table class="table">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($agents as $a)
            <tr>
                <td>{{ $a->name }}</td>
                <td>{{ $a->email }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.agents.destroy', $a) }}" onsubmit="return confirm('Remove agent?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
