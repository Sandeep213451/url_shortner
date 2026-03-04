@extends('layouts.app')

@section('content')
    <h1>Invite New User</h1>

    <div style="max-width: 480px;">
        <form action="{{ route('invite.send') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required
                       placeholder="Full name"
                       value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required
                       placeholder="user@example.com"
                       value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="">-- Select Role --</option>
                    @if(auth()->user()->role === \App\Models\User::ROLE_SUPERADMIN)
                        <option value="{{ \App\Models\User::ROLE_ADMIN }}"
                            {{ old('role') === \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>
                            Admin
                        </option>
                    @else
                        <option value="{{ \App\Models\User::ROLE_ADMIN }}"
                            {{ old('role') === \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="{{ \App\Models\User::ROLE_MEMBER }}"
                            {{ old('role') === \App\Models\User::ROLE_MEMBER ? 'selected' : '' }}>
                            Member
                        </option>
                    @endif
                </select>
            </div>

            @if(auth()->user()->role === \App\Models\User::ROLE_SUPERADMIN)
                <div class="form-group">
                    <label for="company_id">Company</label>
                    <select name="company_id" id="company_id" required>
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Send Invitation</button>
            <a href="{{ route('short-urls.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
